<?php
// payment.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect the data from the form
    $amount = $_POST['amount'];
    $phone = $_POST['phone'];

    // Validate and sanitize input as needed
    $amount = filter_var($amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $phone = filter_var($phone, FILTER_SANITIZE_STRING);

    if (!$amount || !$phone) {
        echo "Invalid input. Please go back and try again.";
        exit;
    }

    // Here, you would integrate with the M-Pesa API or your preferred payment processor
    // Example placeholder message:
    echo "<h3>Processing payment of $amount KSh for phone number $phone.</h3>";
    echo "<p>Please wait...</p>";

    // Uncomment the lines below and add your API code for M-Pesa payment integration
    // e.g., initiate M-Pesa STK Push request using cURL, etc.
    
    // Example success message
    // echo "<p>Payment successful!</p>";
}
else {
    echo "Invalid request method.";
}
?>
