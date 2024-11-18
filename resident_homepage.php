<?php
// Database connection
$host = 'localhost';
$db = 'software';
$user = 'root';
$pass = ''; // Update with your password
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Fetch maintenance requests for a resident
if (isset($_GET['fetch_requests'])) {
    $residentId = 1; // Replace with the logged-in resident's ID (from session)
    $requests = [];
    $sql = "SELECT id, property_number, description, priority, status, created_at, image_path 
            FROM maintenance_requests 
            WHERE resident_id = ? 
            ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $residentId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
    echo json_encode($requests);
    exit;
}


// Ensure the image directory exists
$imageDir = "resident_problem_images";
if (!is_dir($imageDir)) {
    mkdir($imageDir, 0777, true);
}

// Fetch announcements for the front-end
if (isset($_GET['fetch_announcements'])) {
    $announcements = [];
    $sql = "SELECT announce_title, announce_content, announce_date_created FROM helpline_announce ORDER BY announce_date_created DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $announcements[] = $row;
        }
    }

    echo json_encode($announcements);
    exit;
}

// Handle form submission for maintenance request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_maintenance'])) {
    $propertyNumber = $conn->real_escape_string($_POST['propertyNumber']);
    $description = $conn->real_escape_string($_POST['requestDescription']);
    $priority = $conn->real_escape_string($_POST['requestPriority']);
    $residentId = 1; // Replace with session or logged-in resident ID

    $imagePath = null;
    if (isset($_FILES['problemImage']) && $_FILES['problemImage']['error'] == UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['problemImage']['name']);
        $imagePath = $imageDir . '/' . uniqid() . "_" . $fileName;
        move_uploaded_file($_FILES['problemImage']['tmp_name'], $imagePath);
    }

    $stmt = $conn->prepare("INSERT INTO maintenance_requests (resident_id, property_number, description, priority, image_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $residentId, $propertyNumber, $description, $priority, $imagePath);
    if ($stmt->execute()) {
        echo "<script>alert('Maintenance request submitted successfully!');</script>";
    } else {
        echo "<script>alert('Error submitting request: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>
