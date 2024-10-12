<?php
// Include the database connection
include 'db_connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form inputs
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    try {
        // Prepare the SQL statement to find the user by email
        $sql = "SELECT * FROM users WHERE email = :email";
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
                if ($user['role'] == 'Resident') {
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
