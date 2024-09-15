<?php
// Start the session
session_start();

// Database connection settings
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "real estate management system"; 

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize response array
$response = array("status" => "error", "message" => "");

// Check if the form is submitted for registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['firstName'])) {
    // Registration process
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $phone = $_POST['phone'];
    $streetAddress = $_POST['streetAddress'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipCode = $_POST['zipCode'];
    $propertyType = $_POST['propertyType'];
    $budget = $_POST['budget'];

    // SQL query to insert the data into the database
    $sql = "INSERT INTO clienttable (FirstName, LastName, Email, Password, PhoneNumber, StreetAddress, City, StateProvince, ZIPPostalCode, PropertyType, BudgetRange) 
            VALUES ('$firstName', '$lastName', '$email', '$password', '$phone', '$streetAddress', '$city', '$state', '$zipCode', '$propertyType', '$budget')";

    // Execute the query and check if the data was inserted successfully
    if ($conn->query($sql) === TRUE) {
        $response["status"] = "success";
        $response["message"] = "Registration Successful!";
    } else {
        $response["message"] = "Error: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
