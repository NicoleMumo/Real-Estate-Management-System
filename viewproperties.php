<?php
include('config.php'); // Include database connection

// Get properties from the database with filters
$bedroomsFilter = isset($_GET['bedrooms']) ? $_GET['bedrooms'] : '';
$priceMin = isset($_GET['price_min']) ? $_GET['price_min'] : 0;
$priceMax = isset($_GET['price_max']) ? $_GET['price_max'] : 1000000;

$sql = "SELECT p.property_id, p.house_number, p.price_per_month, p.bedrooms, p.description, pi.image_path 
        FROM Properties p
        LEFT JOIN Property_Images pi ON p.property_id = pi.property_id 
        WHERE p.bedrooms LIKE ? AND p.price_per_month BETWEEN ? AND ?
        GROUP BY p.property_id";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $bedroomsFilter, $priceMin, $priceMax);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Properties</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file here -->
</head>
<body>
    <!-- Search Bar -->
    <form method="GET" class="search-form">
        <input type="number" name="bedrooms" placeholder="Bedrooms" value="<?php echo $bedroomsFilter; ?>">
        <input type="number" name="price_min" placeholder="Min Price" value="<?php echo $priceMin; ?>">
        <input type="number" name="price_max" placeholder="Max Price" value="<?php echo $priceMax; ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Property Listings -->
    <div class="property-listings">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="property-card">
                <img src="<?php echo $row['image_path']; ?>" alt="Property Image" class="property-image">
                <h3><?php echo $row['house_number']; ?></h3>
                <p><?php echo $row['bedrooms']; ?> Bedrooms | KSh <?php echo number_format($row['price_per_month']); ?> / month</p>
                <p><?php echo substr($row['description'], 0, 100); ?>...</p>
                <a href="property_details.php?id=<?php echo $row['property_id']; ?>">View More</a>
            </div>
        <?php } ?>
    </div>

</body>
</html>

<?php $stmt->close(); ?>
