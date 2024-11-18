<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support - Rosewood Parks</title>
    <link rel="stylesheet" href="support-style.css">
    <script src="support.js" defer></script>
</head>
<body>

<?php
// Include the database connection
include 'db_connect.php';

// Fetch maintenance requests from the database
$sql = "SELECT id, description, status, property_id FROM maintenance_requests";
$result = $conn->query($sql); // Execute the query
?>

<div class="container">
    <!-- Header -->
    <header>
        <div class="logo">
            <img src="landingpageimages/image-removebg-preview.png" alt="Rosewood Parks Logo">
            <h1>Rosewood Parks</h1>
        </div>
        <nav>
            <ul>
                <li><a href="Helpline_tickets.php">Tickets</a></li>
                <li><a href="announcements.php">Announcements</a></li>
                <li><a href="#communication">Communication</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <section id="maintenance-request" class="card" style="background-image: url('SupportImages/repair-requests.jpeg');">
            <h2>View Tickets</h2>
            <p>View and manage all maintenance requests submitted by residents to ensure quick and effective service.</p>
            <button onclick="openModal('viewRequestsModal')">View Requests</button>
        </section>

        <section id="announcements" class="card" style="background-image: url('SupportImages/maintenance.jpeg');">
            <h2>Announcements</h2>
            <p>View any announcements you have made</p>
            <button onclick="openModal('viewAnnounceModal')">View Announcements</button>
        </section>

        <section id="owner-communication" class="card" style="background-image: url('SupportImages/communication.jpeg');">
            <h2>Communication with Property Owners</h2>
            <p>Maintain clear and direct communication with property owners to facilitate smooth management processes.</p>
            <button onclick="openModal('communicateModal')">Communicate</button>
        </section>
    </main>

    <!-- Footer -->
   
</div>
<footer>
        <p>Contact Information: +254 712-345-678 | rosewoodpark@gmail.com | Kilimani, Nairobi</p>
        <div class="social-media">
            <a href="#"><img src="landingpageimages/instagrampic.png" alt="instagram" height="20px" width="20px"></a> |
            <a href="#"><img src="landingpageimages/social-media.png" alt="tiktok" height="20px" width="20px"></a> |
            <a href="#"><img src="landingpageimages/logo.png" alt="whatsapp" height="20px" width="20px"></a>
        </div>
    </footer>
<!-- Modals -->
<div id="viewRequestsModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('viewRequestsModal')">&times;</span>
        <h2>View Maintenance Requests</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>House Number</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    // Display data from the database
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['description']}</td>
                                <td>{$row['property_id']}</td>
                                <td>{$row['status']}</td>
                              </tr>";
                    }
                } else {
                    // No data found
                    echo "<tr><td colspan='4'>No requests found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div id="communicateModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('communicateModal')">&times;</span>
        <h2>Communicate</h2>
        <p>Messages from property owners will appear here.</p>
    </div>
</div>

<?php
// Close the database connection
$conn->close();
?>

</body>
</html>
