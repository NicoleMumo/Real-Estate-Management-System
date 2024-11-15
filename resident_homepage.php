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

// Ensure the image directory exists
$imageDir = "resident_problem_images";
if (!is_dir($imageDir)) {
    mkdir($imageDir, 0777, true);
}

// Handle form submission
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
