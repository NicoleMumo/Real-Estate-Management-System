<?php
include 'db_connect.php';

$userId = 1; // Replace with the actual logged-in user ID

// Handle form submission for adding properties
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addProperty"])) {
    $houseNumber = $_POST['house_number'];
    $pricePerMonth = $_POST['price_per_month'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    // Handle image upload
    if (isset($_FILES['property_image']) && $_FILES['property_image']['error'] == 0) {
        $imagePath = 'uploads/' . basename($_FILES['property_image']['name']);
        move_uploaded_file($_FILES['property_image']['tmp_name'], $imagePath);
    } else {
        $imagePath = null;
    }

    // Insert property into database
    $propertySql = "INSERT INTO Properties (house_number, price_per_month, location, description, owner_id, image_path) 
                    VALUES (?, ?, ?, ?, ?, ?)";
    $propertyStmt = $conn->prepare($propertySql);
    $propertyStmt->bind_param("sissis", $houseNumber, $pricePerMonth, $location, $description, $userId, $imagePath);
    $propertyStmt->execute();
}

// Fetch bookings for the property owner
$bookingsSql = "SELECT b.booking_id, p.house_number, b.first_name, b.last_name, b.email, b.phone_number 
                FROM Property_Bookings b 
                JOIN Properties p ON b.property_id = p.property_id 
                WHERE p.owner_id = ?";
$bookingsStmt = $conn->prepare($bookingsSql);
$bookingsStmt->bind_param("i", $userId);
$bookingsStmt->execute();
$bookingsResult = $bookingsStmt->get_result();

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
        <li><a href="#" onclick="showSection('bookings')">View Bookings</a></li>
    </ul>
</nav>

<!-- Properties Section -->
<div id="properties" class="form-section">
    <h2>Add Property</h2>
    <form method="post" action="ownerdashboard.php" enctype="multipart/form-data">
        <label for="house_number">House Number:</label><br>
        <input type="text" id="house_number" name="house_number" required><br><br>

        <label for="price_per_month">Price per Month (KSh):</label><br>
        <input type="number" id="price_per_month" name="price_per_month" required><br><br>

        <label for="location">Location:</label><br>
        <input type="text" id="location" name="location" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" required></textarea><br><br>

        <label for="property_image">Property Image:</label><br>
        <input type="file" id="property_image" name="property_image" accept="image/*"><br><br>

        <input type="submit" name="addProperty" value="Add Property" class="btn">
    </form>
</div>

<!-- Bookings Section -->
<div id="bookings" class="form-section">
    <h2>Bookings</h2>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Resident Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Property</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $bookingsResult->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['booking_id']; ?></td>
                    <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone_number']; ?></td>
                    <td><?php echo $row['house_number']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
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
