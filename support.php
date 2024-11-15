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

<div class="container">
    <!-- Header -->
    <header>
        <div class="logo">
            <img src="landingpageimages/image-removebg-preview.png" alt="Rosewood Parks Logo">
            <h1>Rosewood Parks</h1>
        </div>
        <nav>
            <ul>
                <li><a href="#dashboard">Dashboard</a></li>
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

        <section id="property-maintenance" class="card" style="background-image: url('SupportImages/maintenance.jpeg');">
            <h2>Property Maintenance</h2>
            <p>Review and track property maintenance tasks, ensuring timely and efficient upkeep of the properties.</p>
            <button onclick="openModal('viewTasksModal')">View Tasks</button>
        </section>

        <section id="owner-communication" class="card" style="background-image: url('SupportImages/communication.jpeg');">
            <h2>Communication with Property Owners</h2>
            <p>Maintain clear and direct communication with property owners to facilitate smooth management processes.</p>
            <button onclick="openModal('communicateModal')">Communicate</button>
        </section>
    </main>

    <!-- Footer -->
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
        <p>Details about maintenance requests will appear here.</p>
    </div>
</div>

<div id="viewTasksModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('viewTasksModal')">&times;</span>
        <h2>View Tasks</h2>
        <p>Details about property maintenance tasks will appear here.</p>
    </div>
</div>

<div id="communicateModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('communicateModal')">&times;</span>
        <h2>Communicate</h2>
        <p>Messages from property owners will appear here.</p>
    </div>
</div>

</body>
</html>
