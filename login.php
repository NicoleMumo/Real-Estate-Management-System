<?php
session_start(); // Start the session to store user information
include 'db_connect.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // SQL query to find the user by email
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        // Fetch user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Store user data in session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['first_name'] = $user['firstname'];
                $_SESSION['last_name'] = $user['lastname'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on the user's role
                switch ($user['role']) {
                    case 'Resident':
                        header("Location: resident_page.php");
                        exit();
                    case 'PropertyOwner':
                        header("Location: property_owner_page.php");
                        exit();
                    case 'Helpline':
                        header("Location: helpline_page.php");
                        exit();
                    default:
                        echo "Unknown role!";
                }
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "No user found with that email!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
