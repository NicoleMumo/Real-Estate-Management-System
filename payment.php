<?php
if (isset($_POST['submit'])) {
    date_default_timezone_set('Africa/Nairobi');

    # Access token credentials
    $consumerKey = 'hio24i1YVeCB7sAlEoC6iZGaQNWkjucvYWwyR5tgfJNRXI26v';
    $consumerSecret = 'VgibO2MueCX7hvLRiuWI2gcfJ8i2G967FCdGNQY3csHrC7nnC19E9fYGxQ2ULfi2';

    # Define variables
    $BusinessShortCode = '174379';
    $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';

    $PartyA = $_POST['phone']; // Client's phone number
    $AccountReference = '2255';
    $TransactionDesc = 'Test Payment';
    $Amount = $_POST['amount'];

    # Get the timestamp
    $Timestamp = date('YmdHis');

    # Get the base64 encoded password
    $Password = base64_encode($BusinessShortCode . $Passkey . $Timestamp);

    # Header for access token
    $headers = ['Content-Type:application/json; charset=utf8'];

    # M-PESA endpoint URLs
    $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

    # Callback URL
    $CallBackURL = 'https://example.com/callback_url.php';

    # Request access token
    $curl = curl_init($access_token_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
    $result = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $result = json_decode($result);
    $access_token = $result->access_token;
    curl_close($curl);

    # Header for STK push
    $stkheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $access_token];

    # Initiate transaction
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $initiate_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);
    
    $curl_post_data = array(
      'BusinessShortCode' => $BusinessShortCode,
      'Password' => $Password,
      'Timestamp' => $Timestamp,
      'TransactionType' => 'CustomerPayBillOnline',
      'Amount' => $Amount,
      'PartyA' => $PartyA,
      'PartyB' => $BusinessShortCode,
      'PhoneNumber' => $PartyA,
      'CallBackURL' => $CallBackURL,
      'AccountReference' => $AccountReference,
      'TransactionDesc' => $TransactionDesc
    );

    $data_string = json_encode($curl_post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    $curl_response = curl_exec($curl);
    curl_close($curl);

    # Display the response for debugging purposes
    echo "<pre>";
    print_r($curl_response);
    echo "</pre>";
} else {
    # Redirect to payment.html if accessed directly
    header("Location: payment.html");
    exit();
}
?>
