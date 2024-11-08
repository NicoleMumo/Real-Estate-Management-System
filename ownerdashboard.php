<?php
// Include the database connection
include 'db_connect.php';

// Assuming $userId represents the logged-in property owner’s ID
$userId = 1; // Replace with the actual logged-in user ID

// Handle form submission for adding properties
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addProperty"])) {
    $houseNumber = trim($_POST["houseNumber"]);
    $pricePerMonth = floatval($_POST["pricePerMonth"]);
    $bedrooms = intval($_POST["bedrooms"]);
    $description = trim($_POST["description"]);

    // Handle file uploads
    $imagePaths = [];
    $imageDirectory = 'uploads/properties/';
    if (!is_dir($imageDirectory)) {
        mkdir($imageDirectory, 0777, true);
    }

    // Loop through all uploaded images
    foreach ($_FILES['propertyImages']['tmp_name'] as $key => $tmpName) {
        $imageName = basename($_FILES['propertyImages']['name'][$key]);
        $targetFilePath = $imageDirectory . $imageName;
        
        // Check if file is an image
        if (getimagesize($tmpName)) {
            if (move_uploaded_file($tmpName, $targetFilePath)) {
                $imagePaths[] = $targetFilePath;
            }
        }
    }

    // Insert property into the database
    if (count($imagePaths) > 0) {
        // Save the property with the image paths
        $stmt = $conn->prepare("INSERT INTO Properties (house_number, price_per_month, bedrooms, description, owner_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdssi", $houseNumber, $pricePerMonth, $bedrooms, $description, $userId);

        if ($stmt->execute()) {
            $propertyId = $stmt->insert_id;

            // Insert images into the property_images table
            foreach ($imagePaths as $imagePath) {
                $stmtImage = $conn->prepare("INSERT INTO Property_Images (property_id, image_path) VALUES (?, ?)");
                $stmtImage->bind_param("is", $propertyId, $imagePath);
                $stmtImage->execute();
                $stmtImage->close();
            }

            echo "<script>alert('New property added successfully');</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please upload at least one image');</script>";
    }
}

// Fetch tenants data for the property owner
$tenantsSql = "
    SELECT t.tenant_id, t.firstname, t.lastname, t.email, t.phonenumber
    FROM tenants t
    JOIN properties p ON t.property_id = p.property_id
    WHERE p.owner_id = ?";
$tenantsStmt = $conn->prepare($tenantsSql);
$tenantsStmt->bind_param("i", $userId);
$tenantsStmt->execute();
$tenantsResult = $tenantsStmt->get_result();

// Fetch maintenance requests
$maintenanceSql = "
    SELECT mr.request_id, mr.request_details, mr.request_status, mr.request_date,
           t.firstname, t.lastname, p.house_number
    FROM maintenance_requests mr
    JOIN tenants t ON mr.tenant_id = t.tenant_id
    JOIN properties p ON mr.property_id = p.property_id
    WHERE p.owner_id = ?";
$maintenanceStmt = $conn->prepare($maintenanceSql);
$maintenanceStmt->bind_param("i", $userId);
$maintenanceStmt->execute();
$maintenanceResult = $maintenanceStmt->get_result();

// Fetch payments data
$paymentsSql = "
    SELECT pay.payment_id, pay.amount_paid, pay.payment_date, pay.payment_status,
           t.firstname, t.lastname, p.house_number
    FROM payments pay
    JOIN tenants t ON pay.tenant_id = t.tenant_id
    JOIN properties p ON t.property_id = p.property_id
    WHERE p.owner_id = ?";
$paymentsStmt = $conn->prepare($paymentsSql);
$paymentsStmt->bind_param("i", $userId);
$paymentsStmt->execute();
$paymentsResult = $paymentsStmt->get_result();

// Fetch messages for the property owner
$messagesSql = "
    SELECT m.message_id, m.message_content, m.message_type, m.message_date, 
           t.firstname, t.lastname, p.house_number
    FROM messages m
    JOIN properties p ON m.property_id = p.property_id
    JOIN tenants t ON m.tenant_id = t.tenant_id
    WHERE p.owner_id = ?
    ORDER BY m.message_date DESC";
$messagesStmt = $conn->prepare($messagesSql);
$messagesStmt->bind_param("i", $userId);
$messagesStmt->execute();
$messagesResult = $messagesStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Owner Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; }
        header { text-align: center; padding: 20px; }
        nav { margin: 20px 0; }
        nav ul { list-style: none; padding: 0; }
        nav ul li { display: inline; margin: 0 15px; }
        .form-section { display: none; }
        .btn { padding: 10px 20px; background-color: #28a745; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
<div class="logo">
    <img src="landingpageimages/image-removebg-preview.png" alt="logo" width="160" height="130">
</div>
<header>
    <h1>Property Owner Dashboard</h1>
</header>

<nav>
    <ul>
        <li><a href="#" onclick="showSection('properties')">Properties</a></li>
        <li><a href="#" onclick="showSection('tenants')">Tenants Management</a></li>
        <li><a href="#" onclick="showSection('maintenance')">Maintenance Requests</a></li>
        <li><a href="#" onclick="showSection('payments')">Payment Tracking</a></li>
        <li><a href="#" onclick="showSection('messages')">Messages/Notifications</a></li>
    </ul>
</nav>

<!-- Properties Section -->
<div id="properties" class="form-section">
    <h2>Add Property</h2>
    <form method="post" action="ownerdashboard.php" enctype="multipart/form-data">
        <label for="houseNumber">House Number:</label>
        <input type="text" id="houseNumber" name="houseNumber" required>

        <label for="pricePerMonth">Price per Month (KSH):</label>
        <input type="number" id="pricePerMonth" name="pricePerMonth" step="0.01" required>

        <label for="bedrooms">Number of Bedrooms:</label>
        <input type="number" id="bedrooms" name="bedrooms" required>

        <label for="description">Short Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <label for="propertyImages">Property Images:</label>
        <input type="file" id="propertyImages" name="propertyImages[]" accept="image/*" multiple required>

        <input type="submit" name="addProperty" class="btn" value="Add Property">
    </form>
</div>

<!-- Other Sections (Tenants, Maintenance, etc.) -->

<script>
// JavaScript to show and hide sections
function showSection(section) {
    const sections = document.querySelectorAll('.form-section');
    sections.forEach(function(section) {
        section.style.display = 'none';
    });

    const selectedSection = document.getElementById(section);
    selectedSection.style.display = 'block';
}

showSection('properties');
</script>

</body>
</html>
