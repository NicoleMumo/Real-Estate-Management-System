<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpline Tickets - Rosewood Parks</title>
    <link rel="stylesheet" href="support-style.css">
    <script>
        // Ensure only one checkbox is selected at a time for each ticket
        function toggleCheckboxes(rowId, selectedId) {
            const checkboxes = document.querySelectorAll(`.status-checkbox-${rowId}`);
            checkboxes.forEach(checkbox => {
                if (checkbox.id !== selectedId) {
                    checkbox.checked = false;
                }
            });
        }
    </script>
</head>
<body>

<div class="container">
    <header>
        <h1>Rosewood Parks - Helpline Tickets</h1>
    </header>

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

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Handle status update
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id']) && isset($_POST['status'])) {
                    $ticketId = intval($_POST['ticket_id']);
                    $status = $conn->real_escape_string($_POST['status']);
                    $conn->query("UPDATE maintenance_requests SET status = '$status' WHERE id = $ticketId");
                }

                // Fetch maintenance requests
                $result = $conn->query("SELECT * FROM maintenance_requests");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $rowId = $row['id'];
                        echo "<tr>
                                <td>{$rowId}</td>
                                <td>{$row['resident_id']}</td>
                                <td>{$row['description']}</td>
                                <td>" . htmlspecialchars($row['description']) . "</td>
                                <td>
                                    <form method='POST' action=''>
                                        <input type='hidden' name='ticket_id' value='{$rowId}'>
                                        <label>
                                            <input type='checkbox' class='status-checkbox-{$rowId}' id='waiting-{$rowId}' name='status' value='waiting' onchange=\"toggleCheckboxes({$rowId}, 'waiting-{$rowId}')\" " . ($row['status'] === 'waiting' ? 'checked' : '') . "> Waiting
                                        </label>
                                        <label>
                                            <input type='checkbox' class='status-checkbox-{$rowId}' id='in_progress-{$rowId}' name='status' value='in_progress' onchange=\"toggleCheckboxes({$rowId}, 'in_progress-{$rowId}')\" " . ($row['status'] === 'in_progress' ? 'checked' : '') . "> In Progress
                                        </label>
                                        <label>
                                            <input type='checkbox' class='status-checkbox-{$rowId}' id='done-{$rowId}' name='status' value='done' onchange=\"toggleCheckboxes({$rowId}, 'done-{$rowId}')\" " . ($row['status'] === 'done' ? 'checked' : '') . "> Done
                                        </label>
                                        <button type='submit'>Update</button>
                                    </form>
                                </td>
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

    <footer>
        <p>&copy; 2022 Rosewood Parks. All rights reserved.</p>
    </footer>
</div>

</body>
</html>
