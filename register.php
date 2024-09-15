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
    <div class="logo">
        <h3 class="animated-logo">
            <span>Rosewood</span> <span>Park</span>
        </h3>
    <h1>Register New Client</h1>
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

        <!-- Address Information -->
        <fieldset>
            <legend>Address</legend>
            
            <label for="streetAddress">Street Address:</label><br>
            <input type="text" id="streetAddress" name="streetAddress" required><br><br>
            
            <label for="city">City:</label><br>
            <input type="text" id="city" name="city" required><br><br>
            
            <label for="state">State/Province:</label><br>
            <input type="text" id="state" name="state" required><br><br>
            
            <label for="zipCode">ZIP/Postal Code:</label><br>
            <input type="text" id="zipCode" name="zipCode" required><br><br>
        </fieldset>

        <!-- Real Estate Preferences -->
        <fieldset>
            <legend>Real Estate Preferences</legend>
            
            <label for="propertyType">Interested in Property Type:</label><br>
            <select id="propertyType" name="propertyType">
                <option value="residential">Residential</option>
                <option value="commercial">Commercial</option>
            </select><br><br>
            
            <label for="budget">Budget Range (in KSH):</label><br>
            <input type="number" id="budget" name="budget" min="0" required><br><br>
        </fieldset>

        <!-- Submit Button -->
        <button type="submit">Register</button>
    </form>
    <div class="right-section"></div>
</body>
</html>
