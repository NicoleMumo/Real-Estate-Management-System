<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $phone = $_POST['phone'];

    // Check if form data is collected
    echo "First Name: $firstName, Last Name: $lastName, Email: $email, Phone: $phone <br>";

    // SQL query to insert data into the database
    $sql = "INSERT INTO clients (first_name, last_name, email, password, phone) 
            VALUES (:first_name, :last_name, :email, :password, :phone)";

    try {
        // Prepare the statement
        $stmt = $pdo->prepare($sql);

        // Bind the form data to the SQL query
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':phone', $phone);

        // Execute the query
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Failed to register!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
