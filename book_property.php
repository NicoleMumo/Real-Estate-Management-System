<?php
include('db_connect.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["property_id"])) {
    $propertyId = $_POST['property_id'];
    $tenantId = 1; // Replace with actual tenant ID

    $stmt = $conn->prepare("INSERT INTO Bookings (tenant_id, property_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $tenantId, $propertyId);

    if ($stmt->execute()) {
        echo "<script>alert('Booking successful!'); window.location.href='view_properties.php';</script>";
    } else {
        echo "<script>alert('Error in booking. Please try again.');</script>";
    }

    $stmt->close();
}
?>
