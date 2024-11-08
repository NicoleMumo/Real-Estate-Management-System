<?php
include('db_connect.php'); // Include database connection

// Get property details
if (isset($_GET['id'])) {
    $propertyId = $_GET['id'];

    $sql = "SELECT p.property_id, p.house_number, p.price_per_month, p.bedrooms, p.description, pi.image_path 
            FROM Properties p
            LEFT JOIN Property_Images pi ON p.property_id = pi.property_id 
            WHERE p.property_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $propertyId);
    $stmt->execute();
    $result = $stmt->get_result();
    $property = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details</title>
    <link rel="stylesheet" href="propertydetails.css">
</head>
<body>

    <!-- Property Details Container -->
    <div class="property-container">
        <!-- Left Section: Property Details -->
        <div class="property-card">
            <h2><?php echo $property['house_number']; ?></h2>
            <div class="property-images">
                <img src="<?php echo $property['image_path']; ?>" alt="Property Image" class="property-image">
            </div>
            <p><strong><?php echo $property['bedrooms']; ?></strong> Bedrooms</p>
            <p><strong>Description:</strong> <?php echo $property['description']; ?></p>
            <p><strong>Price:</strong> KSh <?php echo number_format($property['price_per_month']); ?> / month</p>
        </div>

        <!-- Right Section: Booking Form and Contact Information -->
        <div class="booking-card">
            <h3>Book This Property</h3>
            <form method="POST" action="book_property.php">
                <input type="hidden" name="property_id" value="<?php echo $property['property_id']; ?>">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="tel" name="phone_number" placeholder="Phone Number" required>
                <button type="submit">Book Now</button>
            </form>

            <h3>Contact Property Owner</h3>
            <a href="https://wa.me/?text=Hi, I am interested in your property at <?php echo $property['house_number']; ?>" target="_blank">
                Contact via WhatsApp
            </a>
            <p>Or call: 123-456-789</p>
        </div>
    </div>

</body>
</html>

<?php $stmt->close(); ?>
