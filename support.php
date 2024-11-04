<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support - Rosewood Parks</title>
    <link rel="stylesheet" href="support-style.css">
</head>
<body>

<div class="container">
    <!-- Header with Logo and Navigation Menu -->
    <header>
        <div class="logo">
            <img src="landingpageimages/image-removebg-preview.png" alt="Rosewood Parks Logo">
            <h1>Rosewood Parks</h1>
        </div>
        <nav>
            <ul>
                <li><a href="#dashboard">Dashboard</a></li>
                <li><a href="#tickets">Tickets</a></li>
                <li><a href="#announcements">Announcements</a></li>
                <li><a href="#communication">Communication</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content Sections -->
    <main>
        <!-- Maintenance Request Section -->
        <section id="maintenance-request" class="card" style="background-image: url('SupportImages/repair-requests.jpeg');">
            <h2>Maintenance Requests</h2>
            <p>View and manage all maintenance requests submitted by residents to ensure quick and effective service.</p>
            <button onclick="openModal('viewRequestsModal')">View Requests</button>
        </section>

        <!-- Resident Issues Section -->
        <section id="resident-issues" class="card" style="background-image: url('SupportImages/resident-issues.jpeg');">
            <h2>Resident Issues</h2>
            <p>Check issues reported by residents to address their concerns and enhance their living experience.</p>
            <button onclick="openModal('checkIssuesModal')">Check Issues</button>
        </section>

        <!-- Property Maintenance Section -->
        <section id="property-maintenance" class="card" style="background-image: url('SupportImages/maintenance.jpeg');">
            <h2>Property Maintenance</h2>
            <p>Review and track property maintenance tasks, ensuring timely and efficient upkeep of the properties.</p>
            <button onclick="openModal('viewTasksModal')">View Tasks</button>
        </section>

        <!-- Communication with Property Owners Section -->
        <section id="owner-communication" class="card" style="background-image: url('SupportImages/communication.jpeg');">
            <h2>Communication with Property Owners</h2>
            <p>Maintain clear and direct communication with property owners to facilitate smooth management processes.</p>
            <button onclick="openModal('communicateModal')">Communicate</button>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>Address: 123 Main St, City</p>
        <p>Phone: 123-456-7890</p>
        <p>Email: info@example.com</p>
        <p>&copy; 2022 Real Estate Management. All rights reserved.</p>
    </footer>
</div>

<!-- Modals -->
<div id="viewRequestsModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('viewRequestsModal')">&times;</span>
        <h2>View Requests</h2>
        <p>Details about maintenance requests...</p>
        <button onclick="confirmAction('viewRequestsModal')">Confirm</button>
    </div>
</div>

<div id="checkIssuesModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('checkIssuesModal')">&times;</span>
        <h2>Check Issues</h2>
        <p>Details about resident issues...</p>
        <button onclick="confirmAction('checkIssuesModal')">Confirm</button>
    </div>
</div>

<div id="viewTasksModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('viewTasksModal')">&times;</span>
        <h2>View Tasks</h2>
        <p>Details about property maintenance tasks...</p>
        <button onclick="confirmAction('viewTasksModal')">Confirm</button>
    </div>
</div>

<div id="communicateModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('communicateModal')">&times;</span>
        <h2>Communicate</h2>
        <p>Details about communication with property owners...</p>
        <button onclick="confirmAction('communicateModal')">Confirm</button>
    </div>
</div>
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
?>

<script src="support.js"></script>
</body>
</html>
