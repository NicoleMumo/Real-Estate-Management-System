<?php
if (isset($_POST['submit'])) {
    date_default_timezone_set('Africa/Nairobi');

    # Access token credentials
    function getAccessToken(){
    $consumerKey = 'hio24i1YVeCB7sAlEoC6iZGaQNWkjucvYWwyR5tgfJNRXI26v';
    $consumerSecret = 'VgibO2MueCX7hvLRiuWI2gcfJ8i2G967FCdGNQY3csHrC7nnC19E9fYGxQ2ULfi2';

    $url = "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";

    try{
      $encodedCredentials = base64_encode($consumerKey.':'.$consumerSecret);

      $headers = [
        'Authorization: Basic ' . $encodedCredentials,
        'Content-Type: application/json'
      ];
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch), true);

        if (curl_errno($ch)) {
          throw new Exception('Failed to get access token: ' . curl_error($ch));
      } else if (isset($response['access_token'])) {
          return $response['access_token'];
      } else {
          throw new Exception('Failed to get access token: ' . $response['error_description']);
      }

      curl_close($ch);
   
    } catch (Exception $error) {
      throw new Exception('Failed to get access token.');
  }
} 