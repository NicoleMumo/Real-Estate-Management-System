<?php
// Include the database connection file
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form input
    $firstname = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Password will be hashed
    $phonenumber = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $role = $_POST['role']; // No need to sanitize because it comes from a dropdown

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Prepare SQL query to insert user data
        $sql = "INSERT INTO users (firstname, lastname, email, password, phonenumber, role) 
                VALUES (:firstname, :lastname, :email, :password, :phonenumber, :role)";

        $stmt = $conn->prepare($sql);

        // Bind the form data to the SQL query
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':phonenumber', $phonenumber);
        $stmt->bindParam(':role', $role);

        // Execute the query
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Registration failed!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;
}
?>
