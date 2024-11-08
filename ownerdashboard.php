<?php
include 'db_connect.php';

$userId = 1; // Replace with the actual logged-in user ID

// Fetch the property owner's name
$ownerSql = "SELECT firstname, lastname FROM PropertyOwners WHERE owner_id = ?";
$ownerStmt = $conn->prepare($ownerSql);
$ownerStmt->bind_param("i", $userId);
$ownerStmt->execute();
$ownerResult = $ownerStmt->get_result();
$owner = $ownerResult->fetch_assoc();
$ownerName = $owner ? $owner['firstname'] . ' ' . $owner['lastname'] : '';

// Check if an action was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $bookingId = $_POST['booking_id'];
    $tenantEmail = $_POST['email'];

    if ($_POST['action'] == 'accept') {
        $subject = "Booking Accepted";
        $message = "Dear Tenant,\n\nYour booking was successful. Please pay a deposit of 10,000 Ksh to reserve the apartment.\n\nThank you,\nProperty Management Team";
    } elseif ($_POST['action'] == 'reject') {
        $subject = "Booking Rejected";
        $message = "Dear Tenant,\n\nUnfortunately, the property has already been booked by someone else. We apologize for any inconvenience.\n\nBest regards,\nProperty Management Team";
    }

    // Send email
    if (mail($tenantEmail, $subject, $message)) {
        echo "<p>Email sent successfully to $tenantEmail.</p>";
    } else {
        echo "<p>Failed to send email to $tenantEmail.</p>";
    }
}

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
        nav ul { list-style: none; padding: 0; }
        nav ul li { display: inline; margin: 0 15px; }
        .form-section { display: none; }
        .btn { padding: 10px 20px; background-color: #28a745; color: white; border: none; cursor: pointer; }
        .btn-reject { background-color: #dc3545; }
        .card { border: 1px solid #ddd; padding: 15px; margin: 10px; width: 250px; }
        .card img { width: 100%; height: auto; }
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

<!-- Bookings Section -->
<div id="bookings" class="form-section">
    <h2>Welcome, <?php echo htmlspecialchars($ownerName); ?></h2>
    <h3>Your Property Bookings</h3>
    <div style="display: flex; flex-wrap: wrap;">
        <?php while ($row = $bookingsResult->fetch_assoc()) { ?>
            <div class="card">
                <?php if ($row['image_path']) { ?>
                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Property Image">
                <?php } else { ?>
                    <p>No image available</p>
                <?php } ?>
                <h4><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></h4>
                <p>Email: <?php echo htmlspecialchars($row['email']); ?></p>
                <p>Phone: <?php echo htmlspecialchars($row['phone_number']); ?></p>
                <p>House Number: <?php echo htmlspecialchars($row['house_number']); ?></p>

                <!-- Accept and Reject Form -->
                <form method="post" action="">
                    <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
                    <button type="submit" name="action" value="accept" class="btn">Accept Booking</button>
                    <button type="submit" name="action" value="reject" class="btn btn-reject">Reject Booking</button>
                </form>
            </div>
        <?php } ?>
    </div>
</div>

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

showSection('bookings'); // Initially show the bookings section
</script>

</body>
</html>
