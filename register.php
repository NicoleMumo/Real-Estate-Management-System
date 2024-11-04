<?php
// Including the database connection file
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $firstname = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Password will be hashed
    $confirmPassword = $_POST['confirmPassword']; // Confirm password
    $phonenumber = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $role = $_POST['role'];

    // Checking if passwords match
    if ($password !== $confirmPassword) {
        echo "Passwords do not match!";
        exit();
    }

    // Hashing the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        
        if ($role === 'PropertyOwner') {
            $sql = "INSERT INTO PropertyOwners (firstname, lastname, email, password, phonenumber, role) 
                    VALUES (:firstname, :lastname, :email, :password, :phonenumber, :role)";
        } elseif ($role === 'Resident') {
            $sql = "INSERT INTO Tenants (firstname, lastname, email, password, phonenumber) 
                    VALUES (:firstname, :lastname, :email, :password, :phonenumber)";
        } elseif ($role === 'Helpline') {
            $sql = "INSERT INTO helpline (firstname, lastname, email, password, phonenumber, role) 
                    VALUES (:firstname, :lastname, :email, :password, :phonenumber, :role)";
        } else {
            throw new Exception("Invalid role selected.");
        }

        $stmt = $conn->prepare($sql);

        
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':phonenumber', $phonenumber);
        
        // Binding the role only for Property Owners and Helpline
        if ($role === 'PropertyOwner' || $role === 'Helpline') {
            $stmt->bindParam(':role', $role);
        }

        // Executing the query
        if ($stmt->execute()) {
            echo "Registration successful!";
            header("Location: login.html");
            exit();
        } else {
            echo "Registration failed!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    // Closing the database connection
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style1.css">
    <script>
        // JavaScript to validate password and confirm password
        function validateForm() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirmPassword").value;
            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="logo">
        <img src="landingpageimages/image-removebg-preview.png" alt="logo" width="160" height="130">
    </div>
    <div>
        <form id="registrationForm" method="POST" action="register.php" onsubmit="return validateForm();">
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
                <input type="password" id="password" name="password" required minlength="8"><br><br>
                
                <label for="confirmPassword">Confirm Password:</label><br>
                <input type="password" id="confirmPassword" name="confirmPassword" required minlength="8"><br><br>
                
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
