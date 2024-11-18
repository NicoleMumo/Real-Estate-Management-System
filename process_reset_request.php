<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'db_connect.php'; // Include your database connection
require 'vendor/autoload.php'; // Autoload PHPMailer

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit();
    }

    try {
        // Check all user tables for the email
        $user = null;
        $userType = null;

        $queries = [
            'tenants' => "SELECT email FROM tenants WHERE email = :email",
            'propertyowners' => "SELECT email FROM propertyowners WHERE email = :email",
            'helpline' => "SELECT email FROM helpline WHERE email = :email"
        ];

        foreach ($queries as $type => $sql) {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $userType = $type;
                break;
            }
        }

        if ($user) {
            // Generate a secure token and expiration time
            $token = bin2hex(random_bytes(50));
            $hashedToken = password_hash($token, PASSWORD_DEFAULT);
            $expires = date("U") + 1800; // 30 minutes

            // Remove any existing tokens for this email
            $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE email = :email");
            $deleteStmt->bindParam(':email', $email);
            $deleteStmt->execute();

            // Insert the new token into the password_resets table
            $insertStmt = $conn->prepare("
                INSERT INTO password_resets (email, token, expires, user_type) 
                VALUES (:email, :token, :expires, :user_type)
            ");
            $insertStmt->bindParam(':email', $email);
            $insertStmt->bindParam(':token', $hashedToken);
            $insertStmt->bindParam(':expires', $expires);
            $insertStmt->bindParam(':user_type', $userType);
            $insertStmt->execute();

            // Send the reset email
            $resetLink = "http://localhost/real-estate-management-system/reset_password.php?token=$token&email=" . urlencode($email);

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'benniahgrey@gmail.com';
                $mail->Password = 'jhsyrznbtiibtybm';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('noreply@example.com', 'Your Website');
                $mail->addAddress($email);
                $mail->Subject = 'Password Reset Request';
                $mail->isHTML(true);
                $mail->Body = "
                    <p>Hi,</p>
                    <p>You requested a password reset. Click the link below to reset your password:</p>
                    <p><a href='$resetLink'>$resetLink</a></p>
                    <p>If you did not request this, you can safely ignore this email.</p>
                    <p>Thanks,<br>Your Website Team</p>
                ";

                $mail->send();
                echo "If your email is registered, you will receive a password reset link shortly.";
            } catch (Exception $e) {
                echo "Error: Unable to send email. Please try again later.";
            }
        } else {
            echo "If your email is registered, you will receive a password reset link shortly.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

$conn = null;
?>