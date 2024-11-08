<?php
$servername = "localhost:3307";
$username = "root";
$password = "oliviamumbi2010";
$dbname = "software"; // Database name updated to "software"

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
