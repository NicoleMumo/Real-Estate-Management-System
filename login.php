<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Prepare the SQL statement using mysqli
    $sql = "SELECT 'PropertyOwner' AS role, owner_id AS id, firstname, lastname, email, password FROM PropertyOwners WHERE email = ?
            UNION ALL
            SELECT 'Tenant' AS role, tenant_id AS id, firstname, lastname, email, password FROM Tenants WHERE email = ?
            UNION ALL
            SELECT 'Helpline' AS role, id AS id, firstname, lastname, email, password FROM helpline WHERE email = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Bind the email parameter for the query
        $stmt->bind_param('sss', $email, $email, $email);
        
        // Execute the query
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
        
        // Check if a user was found
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['firstname'] = $user['firstname'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'Tenant') {
                    header('Location: resident_homepage.html');
                } elseif ($user['role'] === 'PropertyOwner') {
                    header('Location: ownerdashboard.php');
                } elseif ($user['role'] === 'Helpline') {
                    header('Location: support.php');
                }
                exit;
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "No account found with this email!";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the query!";
    }

    // Close the database connection
    $conn->close();
}
?>
