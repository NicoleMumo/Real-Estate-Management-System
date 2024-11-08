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

    try {
        // Insert the user into the correct table based on role
        if ($role === 'PropertyOwner') {
            // Insert into PropertyOwners table
            $stmt = $conn->prepare("INSERT INTO PropertyOwners (firstname, lastname, email, password, phonenumber) VALUES (?, ?, ?, ?, ?)");
        } elseif ($role === 'Tenant') {
            // Insert into Tenants table
            $stmt = $conn->prepare("INSERT INTO Tenants (firstname, lastname, email, password, phonenumber) VALUES (?, ?, ?, ?, ?)");
        } elseif ($role === 'Helpline') {
            // Insert into helpline table
            $stmt = $conn->prepare("INSERT INTO helpline (firstname, lastname, email, password, phonenumber) VALUES (?, ?, ?, ?, ?)");
        } else {
            die("Invalid role selected.");
        }

        // Bind parameters and execute
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $phone);
        
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conn->close();
    }
} else {
    echo "Invalid request method.";
}
?>
