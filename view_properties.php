<?php
include('db_connect.php'); // Include database connection

// Get all properties from the database
$sql = "SELECT p.property_id, p.house_number, p.price_per_month, p.bedrooms, p.description, pi.image_path 
        FROM Properties p
        LEFT JOIN Property_Images pi ON p.property_id = pi.property_id 
        GROUP BY p.property_id";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Properties</title>
    <link rel="stylesheet" href="bookings.css"> <!-- Add your CSS file here -->
</head>
<body>
    <h1>Properties Available for Rent</h1>

    <!-- Property Listings -->
    <div class="property-listings">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="property-card">
                <!-- Display property image -->
                <img src="<?php echo $row['image_path']; ?>" alt="Property Image" class="property-image">

                <!-- Property details -->
                <h3><?php echo $row['house_number']; ?></h3>
                <p><?php echo $row['bedrooms']; ?> Bedrooms | KSh <?php echo number_format($row['price_per_month']); ?> / month</p>
                <p><?php echo substr($row['description'], 0, 100); ?>...</p>

                <!-- Link to property details page -->
                <a href="property_details.php?id=<?php echo $row['property_id']; ?>">View More</a>
            </div>
        <?php } ?>
    </div>

</body>
</html>

<?php $stmt->close(); ?>
