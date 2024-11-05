<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $phonenumber = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $role = $_POST['role'];

    // Server-side password complexity check
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        echo "Password must be at least 8 characters, with uppercase, lowercase, number, and symbol.";
        exit();
    }

    // Checking if passwords match
    if ($password !== $confirmPassword) {
        echo "Passwords do not match!";
        exit();
    }

    // Hashing the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $documentPath = null;
        
        // Handling file upload if the role is Property Owner
        if ($role === 'PropertyOwner') {
            if (isset($_FILES['ownershipDocument']) && $_FILES['ownershipDocument']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['ownershipDocument']['tmp_name'];
                $fileName = basename($_FILES['ownershipDocument']['name']);
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExtensions = array('pdf', 'jpg', 'jpeg', 'png');

                // Verifying file type
                if (!in_array($fileExtension, $allowedExtensions)) {
                    echo "Invalid file type. Only PDF, JPG, JPEG, and PNG are allowed.";
                    exit();
                }

                // Defining and creating upload directory if it doesn't exist
                $uploadDir = 'uploads/documents/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $documentPath = $uploadDir . uniqid() . "_" . $fileName;

                // Moving the uploaded file
                if (!move_uploaded_file($fileTmpPath, $documentPath)) {
                    echo "File upload failed.";
                    exit();
                }
            } else {
                echo "No document uploaded.";
                exit();
            }
        }

        // Inserting data based on role
        if ($role === 'PropertyOwner') {
            $sql = "INSERT INTO PropertyOwners (firstname, lastname, email, password, phonenumber, role, ownership_document) 
                    VALUES (:firstname, :lastname, :email, :password, :phonenumber, :role, :documentPath)";
        } elseif ($role === 'Resident') {
            $sql = "INSERT INTO Tenants (firstname, lastname, email, password, phonenumber) 
                    VALUES (:firstname, :lastname, :email, :password, :phonenumber)";
        } elseif ($role === 'Helpline') {
            $sql = "INSERT INTO helpline (firstname, lastname, email, password, phonenumber, role) 
                    VALUES (:firstname, :lastname, :email, :password, :phonenumber, :role)";
        } else {
            throw new Exception("Invalid role selected.");
        }

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':phonenumber', $phonenumber);

        // Binding role and document path for Property Owner
        if ($role === 'PropertyOwner') {
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':documentPath', $documentPath);
        } elseif ($role === 'Helpline') {
            $stmt->bindParam(':role', $role);
        }

        if ($stmt->execute()) {
            echo "Registration successful!";
            header("Location: login.html");
            exit();
        } else {
            echo "Registration failed!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;
}
?>
