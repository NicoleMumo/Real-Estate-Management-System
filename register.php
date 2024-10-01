<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Real Estate Management</title>
    <link rel="stylesheet" href="style1.css">
    <script src="script.js" defer></script>
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
    <form id="registrationForm">
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
        </fieldset>

        <!-- Submit Button -->
        <button type="submit">Register</button>
    </form>
    </div>
    <div class="right-section"></div>
</body>
</html>