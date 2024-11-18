<?php
session_start(); // Start the session to access session variables

if (isset($_POST['submit'])) {
    include 'db_connect.php';

    $amount = $_POST['amount'];
    $phoneNumber = $_POST['phone'];

    // Check if session variables are set
    if (!isset($_SESSION['tenant_id'], $_SESSION['property_id'])) {
        echo "<script>alert('Session data missing. Please log in again.');</script>";
        exit;
    }

    // Retrieve tenant_id and property_id from the session
    $tenant_id = $_SESSION['tenant_id'];
    $property_id = $_SESSION['property_id'];

    // Save Payment Request to Database
    $sql = "INSERT INTO Payments (tenant_id, property_id, amount_paid, payment_status) 
            VALUES (?, ?, ?, 'Pending')"; // Use parameterized query to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iid", $tenant_id, $property_id, $amount); // 'i' for integer (tenant_id and property_id), 'd' for double (amount)

    if ($stmt->execute()) {
        echo "<script>alert('Payment request saved successfully. Proceeding with STK Push.');</script>";

        // Proceed with Daraja API Integration
        $consumerKey = 'sXhpmN7WH0i5MLgrcZcgxt48LFPMf1hgBWCufkCzjbfMIbWz';
        $consumerSecret = 'jGfpNRZcQZgWrnCJT6Ag0AxwDY4sPfv3Q7f5PLZDftwGqTuBi9j2bETHNDVM4hRW';
        $shortCode = "174379";
        $passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
        $timestamp = date('YmdHis');
        $password = base64_encode($shortCode . $passkey . $timestamp);

        // Get Access Token
        $accessTokenUrl = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $credentials = base64_encode($consumerKey . ':' . $consumerSecret);
        $headers = ['Authorization: Basic ' . $credentials];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $accessTokenUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (isset($response['access_token'])) {
            $accessToken = $response['access_token'];

            // STK Push Request
            $stkPushUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            $callbackUrl = 'https://localhost.run/docs/forever-free/callback.php'; // Replace with your actual callback URL

            $stkPushData = [
                'BusinessShortCode' => $shortCode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $amount,
                'PartyA' => 254743751534,
                'PartyB' => $shortCode,
                'PhoneNumber' => $phoneNumber,
                'CallBackURL' => $callbackUrl,
                'AccountReference' => 'TestPayment',
                'TransactionDesc' => 'Payment for service'
            ];

            $stkHeaders = [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $stkPushUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $stkHeaders);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($stkPushData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $stkResponse = json_decode(curl_exec($ch), true);
            curl_close($ch);

            if (isset($stkResponse['ResponseCode']) && $stkResponse['ResponseCode'] == '0') {
                echo "<script>alert('STK Push sent successfully! Check your phone.');</script>";
            } else {
                echo "<script>alert('Failed to send STK Push. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Failed to get access token.');</script>";
        }
    } else {
        echo "<script>alert('Failed to save payment request to database.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lipa na Mpesa</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        body {
            background-color: #eaedf4;
            font-family: "Rubik", sans-serif;
        }
        .card {
            width: 350px;
            border: none;
            border-radius: 15px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .payment-tabs {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        .payment-tab {
            padding: 10px 20px;
            border-radius: 20px;
            color: #8d9297;
            background-color: #f3f4f6;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
        }
        .payment-tab.active {
            background-color: #28a745;
            color: #fff;
        }
        .form-label {
            font-weight: bold;
            color: #333;
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
            border-radius: 8px;
            font-weight: bold;
        }
        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center mt-5">
        <div class="card">
            <!-- Payment Tabs -->
            <div class="payment-tabs">
                <div class="payment-tab active">Mpesa</div>
                <div class="payment-tab">Paypal</div>
                <div class="payment-tab">Card</div>
            </div>
            <!-- Payment Header -->
            <div class="media mb-4">
                <img src="./images/1200px-M-PESA_LOGO-01.svg.png" height="50" class="mr-3" alt="Mpesa Logo">
                <div class="media-body"><br>
                    <h6 class="mt-2">Enter Amount & Number</h6>
                </div>
            </div>
            <!-- Payment Form -->
            <form action="payment.php" method="POST">
                <div class="form-group">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="text" class="form-control" name="amount" placeholder="Enter Amount" required>
                </div>
                <div class="form-group mt-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" name="phone" placeholder="Enter Phone Number" required>
                </div>
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-success btn-block" name="submit" value="submit">Pay</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>



