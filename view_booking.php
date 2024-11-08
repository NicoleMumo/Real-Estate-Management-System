<?php
include 'db_connect.php';

$ownerId = $_GET['owner_id'];

// Fetch property owner name for the welcome message
$ownerSql = "SELECT firstname, lastname FROM Owners WHERE owner_id = ?";
$ownerStmt = $conn->prepare($ownerSql);
$ownerStmt->bind_param("i", $ownerId);
$ownerStmt->execute();
$ownerResult = $ownerStmt->get_result();
$owner = $ownerResult->fetch_assoc();

// Fetch bookings related to this owner's properties
$bookingsSql = "SELECT b.booking_id, b.first_name, b.last_name, b.email, b.phone_number, 
                p.house_number, p.image_path
                FROM Property_Bookings b
                JOIN Properties p ON b.property_id = p.property_id
                WHERE p.owner_id = ?";
$bookingsStmt = $conn->prepare($bookingsSql);
$bookingsStmt->bind_param("i", $ownerId);
$bookingsStmt->execute();
$bookingsResult = $bookingsStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { text-align: center; }
        .card-container { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
        .card { border: 1px solid #ddd; padding: 20px; width: 250px; text-align: center; border-radius: 8px; }
        .card img { width: 100%; height: auto; border-radius: 8px; }
        .card h3 { margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo $owner['firstname'] . ' ' . $owner['lastname']; ?></h1>
    <h2>Booking Details</h2>
    <div class="card-container">
        <?php while ($booking = $bookingsResult->fetch_assoc()) { ?>
            <div class="card">
                <img src="<?php echo $booking['image_path']; ?>" alt="Property Image">
                <h3>House Number: <?php echo $booking['house_number']; ?></h3>
                <p>Booked by: <?php echo $booking['first_name'] . ' ' . $booking['last_name']; ?></p>
                <p>Email: <?php echo $booking['email']; ?></p>
                <p>Phone: <?php echo $booking['phone_number']; ?></p>
            </div>
        <?php } ?>
    </div>
</body>
</html>
