<?php
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

// Fetch property owner's name
$ownerSql = "SELECT firstname, lastname FROM PropertyOwners WHERE owner_id = ?";
$ownerStmt = $conn->prepare($ownerSql);
$ownerStmt->bind_param("i", $userId);
$ownerStmt->execute();
$ownerResult = $ownerStmt->get_result();
$owner = $ownerResult->fetch_assoc();
$ownerName = $owner ? $owner['firstname'] . ' ' . $owner['lastname'] : '';

// Fetch bookings for the property owner along with property images
$bookingsSql = "SELECT b.booking_id, p.house_number, b.first_name, b.last_name, b.email, b.phone_number, i.image_path 
                FROM Property_Bookings b 
                JOIN Properties p ON b.property_id = p.property_id 
                LEFT JOIN Property_Images i ON p.property_id = i.property_id
                WHERE p.owner_id = ?";
$bookingsStmt = $conn->prepare($bookingsSql);
$bookingsStmt->bind_param("i", $userId);
$bookingsStmt->execute();
$bookingsResult = $bookingsStmt->get_result();

// Fetch tenants, maintenance requests, payments, and messages
$tenantsSql = "SELECT t.tenant_id, t.firstname, t.lastname, t.email, t.phonenumber
                FROM tenants t
                JOIN properties p ON t.property_id = p.property_id
                WHERE p.owner_id = ?";
$tenantsStmt = $conn->prepare($tenantsSql);
$tenantsStmt->bind_param("i", $userId);
$tenantsStmt->execute();
$tenantsResult = $tenantsStmt->get_result();

$maintenanceSql = "SELECT mr.request_id, mr.request_details, mr.request_status, mr.request_date, 
                   t.firstname, t.lastname, p.house_number
                   FROM maintenance_requests mr
                   JOIN tenants t ON mr.tenant_id = t.tenant_id
                   JOIN properties p ON mr.property_id = p.property_id
                   WHERE p.owner_id = ?";
$maintenanceStmt = $conn->prepare($maintenanceSql);
$maintenanceStmt->bind_param("i", $userId);
$maintenanceStmt->execute();
$maintenanceResult = $maintenanceStmt->get_result();

$paymentsSql = "SELECT pay.payment_id, pay.amount_paid, pay.payment_date, pay.payment_status, 
                t.firstname, t.lastname, p.house_number
                FROM payments pay
                JOIN tenants t ON pay.tenant_id = t.tenant_id
                JOIN properties p ON t.property_id = p.property_id
                WHERE p.owner_id = ?";
$paymentsStmt = $conn->prepare($paymentsSql);
$paymentsStmt->bind_param("i", $userId);
$paymentsStmt->execute();
$paymentsResult = $paymentsStmt->get_result();

$messagesSql = "SELECT m.message_id, m.message_content, m.message_type, m.message_date, 
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
        /* General styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
            font-size: 16px;
            line-height: 1.6;
        }

        .logo img {
            height: 100px;
            width: 130px;
            display: block;
            margin-left: 5px ;

        }

        header {
            text-align: center;
            background-color: #4CAF50;
            color: white;
            padding: 5px;
        }

        header h1 {
            margin-bottom: 10px;
        }

        /* Navigation */
        nav ul {
            list-style-type: none;
            text-align: center;
            background-color: #333;
            padding: 15px 0;
        }

        nav ul li {
            display: inline;
            margin: 0 15px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        nav ul li a:hover {
            background-color: #4CAF50;
        }

        /* Form section styles */
        .form-section {
            display: none;
            padding: 20px;
            background-color: white;
            margin: 20px auto;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h3 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"], input[type="number"], textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #218838;
        }

        /* Card section styles */
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            padding: 15px;
            margin: 10px;
            width: 250px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .card h4 {
            margin: 10px 0;
            font-size: 18px;
            color: #4CAF50;
        }

        .card p {
            font-size: 14px;
            color: #555;
        }

        /* JavaScript - Display selected section */
        .active {
            display: block !important;
        }
    </style>
</head>
<body>
<div class="logo">
    <img src="landingpageimages/image-removebg-preview.png" alt="logo" width="160" height="130">
</div>
<header>
    <h1>Property Owner Dashboard</h1>
    <h2>Welcome, <?php echo htmlspecialchars($ownerName); ?></h2>
</header>

<nav>
    <ul>
        <li><a href="#" onclick="showSection('properties')">Properties</a></li>
        <li><a href="#" onclick="showSection('tenants')">Tenants Management</a></li>
        <li><a href="#" onclick="showSection('maintenance')">Maintenance Requests</a></li>
        <li><a href="#" onclick="showSection('payments')">Payment Tracking</a></li>
        <li><a href="#" onclick="showSection('messages')">Messages/Notifications</a></li>
        <li><a href="#" onclick="showSection('bookings')">View Bookings</a></li>
    </ul>
</nav>

<!-- Properties Section -->
<div id="properties" class="form-section active">
    <h3>Add Property</h3>
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

<!-- Bookings Section -->
<div id="bookings" class="form-section">
    <h3>Your Property Bookings</h3>
    <div style="display: flex; flex-wrap: wrap; justify-content: center;">
        <?php while ($row = $bookingsResult->fetch_assoc()) { ?>
            <div class="card">
                <?php if ($row['image_path']) { ?>
                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Property Image">
                <?php } ?>
                <h4>House Number: <?php echo htmlspecialchars($row['house_number']); ?></h4>
                <p>Booking by: <?php echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></p>
                <p>Email: <?php echo htmlspecialchars($row['email']); ?></p>
                <p>Phone: <?php echo htmlspecialchars($row['phone_number']); ?></p>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    function showSection(sectionId) {
        const sections = document.querySelectorAll('.form-section');
        sections.forEach(section => section.classList.remove('active'));
        document.getElementById(sectionId).classList.add('active');
    }
</script>
</body>
</html>
