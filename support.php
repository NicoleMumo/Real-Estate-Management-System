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
        <section id="maintenance-request" class="card" style="background-image: url('SupportImages/resident-requests.jpeg');">
            <h2>Maintenance Requests</h2>
            <p>View and manage all maintenance requests submitted by residents to ensure quick and effective service.</p>
            <button onclick="viewRequests()">View Requests</button>
        </section>

        <!-- Resident Issues Section -->
        <section id="resident-issues" class="card" style="background-image: url('SupportImages/repairrequests.jpeg');">
            <h2>Resident Issues</h2>
            <p>Check issues reported by residents to address their concerns and enhance their living experience.</p>
            <button onclick="checkIssues()">Check Issues</button>
        </section>

        <!-- Property Maintenance Section -->
        <section id="property-maintenance" class="card" style="background-image: url('SupportImages/maintenance.jpeg');">
            <h2>Property Maintenance</h2>
            <p>Review and track property maintenance tasks, ensuring timely and efficient upkeep of the properties.</p>
            <button onclick="viewTasks()">View Tasks</button>
        </section>

        <!-- Communication with Property Owners Section -->
        <section id="owner-communication" class="card" style="background-image: url('SupportImages/communication.jpeg');">
            <h2>Communication with Property Owners</h2>
            <p>Maintain clear and direct communication with property owners to facilitate smooth management processes.</p>
            <button onclick="communicate()">Communicate</button>
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

<script src="script.js"></script>
</body>
</html>
