<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $role = $_POST['role'] ?? '';

    // Check if password fields match
    if ($password !== $confirmPassword) {
        die("Passwords do not match!");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, phonenumber, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $firstName, $lastName, $email, $hashedPassword, $phone, $role);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
