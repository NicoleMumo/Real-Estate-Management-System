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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Real Estate Management</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div>
        <div class="logo">
            <h3 class="animated-logo">
                <span>Rosewood</span> <span>Park</span>
            </h3>
        </div>
        <h1>Register New Client</h1> 
    </div>
    <div>
        <form id="registrationForm" method="POST" action="register.php">
            <!-- Personal Information -->
            <fieldset>
                <legend>Personal Information</legend>
                
                <label for="firstName">First Name:</label><br>
                <input type="text" id="firstName" name="firstName" required><br><br>
                
                <label for="lastName">Last Name:</label><br>
                <input type="text" id="lastName" name="lastName" required><br><br>
                
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required><br><br>
                
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br><br>
                
                <label for="phone">Phone Number:</label><br>
                <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required><br><br>
                
                <!-- User Role Dropdown -->
                <label for="role">Register as:</label><br>
                <select id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="Resident">Resident</option>
                    <option value="PropertyOwner">Property Owner</option>
                    <option value="Helpline">Helpline</option>
                </select><br><br>
            </fieldset>
        
            <!-- Submit Button -->
            <button type="submit">Register</button>
        </form>        
    </div>
    <div class="right-section"></div>
</body>
</html>
git 