<?php
// Include the database connection
include 'db_connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form inputs
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    try {
        // Prepare the SQL statement to find the user by email in the PropertyOwners table
        $sql = "SELECT 'PropertyOwner' AS role, owner_id AS id, firstname, lastname, email, password FROM PropertyOwners WHERE email = :email
                UNION ALL
                SELECT 'Tenant' AS role, tenant_id AS id, firstname AS firstname, lastname AS lastname, email, password FROM Tenants WHERE email = :email
                UNION ALL
                SELECT 'Helpline' AS role, id AS id, firstname, lastname, email, password FROM helpline WHERE email = :email";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Check if a user with the provided email exists
        if ($stmt->rowCount() > 0) {
            // Fetch the user data
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the provided password with the hashed password in the database
            if (password_verify($password, $user['password'])) {
                // Start the session and store user information
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['firstname'] = $user['firstname'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on user role
                if ($user['role'] == 'Tenant') {
                    header('Location: resident_homepage.html');
                } elseif ($user['role'] == 'PropertyOwner') {
                    header('Location: ownerdashboard.php');
                } elseif ($user['role'] == 'Helpline') {
                    header('Location: helplinedashboard.php');
                }
                exit;
            } else {
                // Password is incorrect
                echo "Invalid password!";
            }
        } else {
            // No user found with that email
            echo "No account found with this email!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $conn = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Real Estate Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="logo">
        <img src="landingpageimages/image-removebg-preview.png" alt="logo" width="160" height="130">
    </div>
    <form action="login.php" method="POST"> 
        <!-- Email Input -->
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <!-- Password Input -->
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <!-- Submit Button -->
        <button type="submit">Login</button>

        <!-- Optional Links for Registration/Password Reset -->
        <p>
            Don't have an account? <a href="register.html">Register here</a><br>
            Forgot your password? <a href="#">Reset here</a>
        </p>
    </form>
</body>
</html>

