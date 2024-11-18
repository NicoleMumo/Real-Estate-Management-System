<?php
include 'db_connect.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = :token AND expires >= :now");
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':now', date("U"));
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];

            // Check if passwords match and meet complexity requirements
            if ($password !== $confirmPassword) {
                echo "Passwords do not match.";
        
            } else {
                // Hash the new password and update it in the users table
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Get email from password_resets table
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $email = $row['email'];

                // Update password in users table
                $updateStmt = $conn->prepare("UPDATE users SET password = :password WHERE email = :email");
                $updateStmt->bindParam(':password', $hashedPassword);
                $updateStmt->bindParam(':email', $email);
                $updateStmt->execute();

                // Delete the password reset record
                $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();

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
