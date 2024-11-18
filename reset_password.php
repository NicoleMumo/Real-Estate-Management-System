<?php
include 'db_connect.php'; // Include your MySQLi connection file

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND expires >= ?");
    $current_time = date("U");
    $stmt->bind_param('si', $token, $current_time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];

            // Check if passwords match
            if ($password !== $confirmPassword) {
                echo "Passwords do not match.";
            } else {
                // Hash the new password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Get email from password_resets table
                $row = $result->fetch_assoc();
                $email = $row['email'];

                // Update password in the appropriate user table
                $stmt = $conn->prepare("
                    UPDATE users 
                    SET password = ? 
                    WHERE email = ?
                ");
                $stmt->bind_param('ss', $hashedPassword, $email);
                $stmt->execute();

                // Delete the token from the password_resets table
                $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
                $deleteStmt->bind_param('s', $email);
                $deleteStmt->execute();

                echo "Password has been reset successfully!";
            }
        }
    } else {
        echo "Invalid or expired reset link.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <form action="" method="post">
        <h2>Set New Password</h2>
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>
        
        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required>
        
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
