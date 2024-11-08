<?php
include('db_connect.php');

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details</title>
    <link rel="stylesheet" href="bookings.css">
</head>
<body>
    <h1>Property Details</h1>

    <div class="property-details">
        <div class="property-image-container">
            <img src="<?php echo $property['image_path']; ?>" alt="Property Image" class="property-image">
        </div>

        <h2><?php echo $property['house_number']; ?></h2>
        <p><?php echo $property['bedrooms']; ?> Bedrooms</p>
        <p>Price: KSh <?php echo number_format($property['price_per_month']); ?> / month</p>
        <p>Description: <?php echo $property['description']; ?></p>

        <form method="POST" action="book_property.php">
            <input type="hidden" name="property_id" value="<?php echo $property['property_id']; ?>">
            <input type="submit" value="Book Now" class="btn">
        </form>
    </div>
</body>
</html>
