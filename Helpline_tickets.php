<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpline Tickets - Rosewood Parks</title>
    <link rel="stylesheet" href="support-style.css">
</head>
<body>

<div class="container">
    <!-- Header -->
    <header>
        <h1>Rosewood Parks - Helpline Tickets</h1>
    </header>

    <!-- Tickets Table -->
    <main>
        <h2>Maintenance Requests</h2>
        <table>
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Tenant ID</th>
                    <th>Property ID</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection
                $host = 'localhost';
                $db = 'software';
                $user = 'root';
                $pass = ''; // Update password
                $conn = new mysqli($host, $user, $pass, $db);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch maintenance requests
                $result = $conn->query("SELECT * FROM maintenance_requests");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['resident_id']}</td>
                                <td>{$row['description']}</td>
                                <td>" . htmlspecialchars($row['description']) . "</td>
                                <td>{$row['status']}</td>
                                <td>{$row['created_at']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No tickets found.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2022 Rosewood Parks. All rights reserved.</p>
    </footer>
</div>

</body>
</html>
