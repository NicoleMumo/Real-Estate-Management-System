<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    // Checking if the email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Generatinng a unique token and expiration time
        $token = bin2hex(random_bytes(50));
        $expires = date("U") + 1800; // 30 minutes expiration time

        // Storing the token and expiration in a password_resets table
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires) VALUES (:email, :token, :expires)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expires', $expires);
        $stmt->execute();

        // Sending reset email with link
        $resetLink = "http://yourdomain.com/reset_password.php?token=" . $token;
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: " . $resetLink;
        $headers = "From: noreply@yourdomain.com";

        mail($to, $subject, $message, $headers);

        echo "A reset link has been sent to your email address.";
    } else {
        echo "Email address not found.";
    }
}
?>
