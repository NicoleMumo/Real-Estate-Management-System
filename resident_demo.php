<?php
// Database connection
$host = 'localhost';
$db = 'rosewood_park';
$user = 'root';
$pass = ''; // Update this
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling form submission for maintenance request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_maintenance'])) {
    $description = $conn->real_escape_string($_POST['requestDescription']);
    $priority = $conn->real_escape_string($_POST['requestPriority']);
    $resident_id = 1; // Example resident ID

    $sql = "INSERT INTO maintenance_requests (resident_id, description, priority) VALUES ('$resident_id', '$description', '$priority')";
    $conn->query($sql);
}

// Handling form submission for issue reporting
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_issue'])) {
    $description = $conn->real_escape_string($_POST['issueDescription']);
    $issue_type = $conn->real_escape_string($_POST['issueType']);
    $resident_id = 1; // Example resident ID

    $sql = "INSERT INTO resident_issues (resident_id, description, issue_type) VALUES ('$resident_id', '$description', '$issue_type')";
    $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Demo</title>
    <link rel="stylesheet" href="resident_demo.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Resident Portal</h1>
    </header>

    <main>
        <section>
            <h2>Submit Maintenance Request</h2>
            <form method="POST" action="resident_demo.php">
                <label for="requestDescription">Description:</label>
                <textarea id="requestDescription" name="requestDescription" required></textarea>

                <label for="requestPriority">Priority:</label>
                <select id="requestPriority" name="requestPriority" required>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>

                <button type="submit" name="submit_maintenance">Submit Request</button>
            </form>
        </section>

        <section>
            <h2>Report an Issue</h2>
            <form method="POST" action="resident_demo.php">
                <label for="issueDescription">Description:</label>
                <textarea id="issueDescription" name="issueDescription" required></textarea>

                <label for="issueType">Issue Type:</label>
                <select id="issueType" name="issueType" required>
                    <option value="noise">Noise</option>
                    <option value="safety">Safety</option>
                    <option value="other">Other</option>
                </select>

                <button type="submit" name="submit_issue">Report Issue</button>
            </form>
        </section>
    </main>
</div>

<script src="resident_demo.js"></script>
</body>
</html>
