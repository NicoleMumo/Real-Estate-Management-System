<?php
include 'db_connect.php';  
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Handling form submission for maintenance request by owner
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_owner_request'])) {
    $description = $conn->real_escape_string($_POST['ownerRequestDescription']);
    $owner_id = 1; // Example owner ID

    $sql = "INSERT INTO owner_requests (owner_id, description) VALUES ('$owner_id', '$description')";
    $conn->query($sql);
}

// Handling form submission for communication with support
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_communication'])) {
    $message = $conn->real_escape_string($_POST['communicationMessage']);
    $owner_id = 1; // Example owner ID

    $sql = "INSERT INTO owner_communications (owner_id, message) VALUES ('$owner_id', '$message')";
    $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Demo</title>
    <link rel="stylesheet" href="owner_demo_style.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Owner Portal</h1>
    </header>

    <main>
        <section>
            <h2>Submit Maintenance Request</h2>
            <form method="POST" action="owner_demo.php">
                <label for="ownerRequestDescription">Description:</label>
                <textarea id="ownerRequestDescription" name="ownerRequestDescription" required></textarea>

                <button type="submit" name="submit_owner_request">Submit Request</button>
            </form>
        </section>

        <section>
            <h2>Communicate with Support</h2>
            <form method="POST" action="owner_demo.php">
                <label for="communicationMessage">Message:</label>
                <textarea id="communicationMessage" name="communicationMessage" required></textarea>

                <button type="submit" name="submit_communication">Send Message</button>
            </form>
        </section>
    </main>
</div>

<script src="owner_demo.js"></script>
</body>
</html>
