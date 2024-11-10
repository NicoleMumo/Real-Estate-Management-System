<?php
include('db_connect.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["property_id"])) {
    $propertyId = $_POST['property_id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];

    // Insert booking details into Property_Bookings table
    $stmt = $conn->prepare("INSERT INTO Property_Bookings (property_id, first_name, last_name, email, phone_number) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $propertyId, $firstName, $lastName, $email, $phoneNumber);

    if ($stmt->execute()) {
        echo "<script>alert('Booking successful!'); window.location.href='view_properties.php';</script>";
    } else {
        echo "<script>alert('Error in booking. Please try again.');</script>";
    }

    $stmt->close();
}
?>
