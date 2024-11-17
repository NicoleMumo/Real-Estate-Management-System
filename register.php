<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db_connect.php'; // Ensure this file contains the correct database connection details

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $role = $_POST['role'] ?? '';
    $property_id = $_POST['property_id'] ?? null;

    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword) || empty($phone) || empty($role)) {
        die("All fields are required!");
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        die("Passwords do not match!");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        if ($role === 'Tenant') {
            // Validate property_id for tenants
            if (empty($property_id)) {
                die("Property ID is required for tenants!");
            }

            // Insert tenant into the database
            $stmt = $conn->prepare(
                "INSERT INTO Tenants (firstname, lastname, email, password, phonenumber, property_id) VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("ssssss", $firstName, $lastName, $email, $hashedPassword, $phone, $property_id);

        } elseif ($role === 'PropertyOwner') {
            // Insert property owner
            $stmt = $conn->prepare(
                "INSERT INTO PropertyOwners (firstname, lastname, email, password, phonenumber) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $phone);

        } elseif ($role === 'Helpline') {
            // Insert helpline
            $stmt = $conn->prepare(
                "INSERT INTO Helpline (firstname, lastname, email, password, phonenumber) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $phone);

        } else {
            die("Invalid role selected.");
        }

        // Execute the query
        if ($stmt->execute()) {
            header("Location: success.html");
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
