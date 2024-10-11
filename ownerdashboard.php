<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Owner Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styles for the dashboard */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #333;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .content {
            padding: 20px;
        }

        /* Hide all sections initially */
        .section {
            display: none;
        }

        /* Show active section */
        .active {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <!-- Horizontal Navbar -->
    <div class="navbar">
        <a href="#" onclick="showSection('properties')">Properties</a>
        <a href="#" onclick="showSection('tenants')">Tenants Management</a>
        <a href="#" onclick="showSection('maintenance')">Maintenance Requests</a>
        <a href="#" onclick="showSection('payments')">Payment Tracking</a>
        <a href="#" onclick="showSection('messages')">Messages/Notifications</a>
    </div>

    <!-- Content Sections -->
    <div class="content">

        <!-- Properties Section -->
        <div id="properties" class="section">
            <h2>Properties</h2>
            <p>Here you can add new properties and view available houses.</p>

            <!-- Add Property Form -->
            <form method="POST" action="server.php">
                <input type="text" name="propertyName" placeholder="Property Name" required>
                <input type="text" name="location" placeholder="Location" required>
                <input type="number" name="price" placeholder="Price" required>
                <input type="number" name="numRooms" placeholder="Number of Rooms" required>
                <button type="submit">Add Property</button>
            </form>

            <!-- Button to View Properties -->
            <button onclick="viewProperties()">View Properties</button>

            <!-- Section to display properties fetched from the database -->
            <div id="propertiesList"></div>
        </div>

        <!-- Tenants Management Section -->
        <div id="tenants" class="section">
            <h2>Tenants Management</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tenant ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone Number</th> <!-- Added Phone Number -->
                        <th>Street Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>ZIP Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- PHP loop for tenants would go here -->
                    <?php
                    // Database connection and fetching tenant data
                    $conn = new mysqli("localhost", "root", "", "real_estate_management_system");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT UserID, FirstName, LastName, Email, PhoneNumber, StreetAddress, City, StateProvince, ZIPPostalCode FROM clienttable";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['UserID']}</td>";
                            echo "<td>{$row['FirstName']}</td>";
                            echo "<td>{$row['LastName']}</td>";
                            echo "<td>{$row['Email']}</td>";
                            echo "<td>{$row['PhoneNumber']}</td>";
                            echo "<td>{$row['StreetAddress']}</td>";
                            echo "<td>{$row['City']}</td>";
                            echo "<td>{$row['StateProvince']}</td>";
                            echo "<td>{$row['ZIPPostalCode']}</td>";
                            echo "<td><button>Edit</button> <button>Delete</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10'>No tenants found</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Maintenance Requests Section -->
        <div id="maintenance" class="section">
            <h2>Maintenance Requests</h2>
            <p>Manage maintenance requests from tenants.</p>
            <button>View Requests</button>
            <button>Add New Request</button>
        </div>

        <!-- Payment Tracking Section -->
        <div id="payments" class="section">
            <h2>Payment Tracking</h2>
            <p>View payment history, upcoming payments, and outstanding rent balances.</p>
            <button>View Payments</button>
        </div>

        <!-- Messages/Notifications Section -->
        <div id="messages" class="section">
            <h2>Messages/Notifications</h2>
            <p>Check important updates such as maintenance alerts, tenant messages, and payment reminders.</p>
            <button>View Messages</button>
        </div>

    </div>

    <!-- JavaScript to switch between sections and handle dynamic loading -->
    <script>
        function showSection(sectionId) {
            // Hide all sections
            var sections = document.getElementsByClassName('section');
            for (var i = 0; i < sections.length; i++) {
                sections[i].style.display = 'none';
            }

            // Show the selected section
            document.getElementById(sectionId).style.display = 'block';
        }

        // Show the Properties section by default on page load
        document.addEventListener("DOMContentLoaded", function() {
            showSection('properties');
        });

        // Function to fetch and display properties
        function viewProperties() {
            var propertiesList = document.getElementById('propertiesList');
            propertiesList.innerHTML = '';

            // Make an AJAX request to fetch properties (you can create an API endpoint for this)
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_properties.php', true);
            xhr.onload = function () {
                if (this.status === 200) {
                    propertiesList.innerHTML = this.responseText;
                } else {
                    propertiesList.innerHTML = '<p>Error fetching properties.</p>';
                }
            };
            xhr.send();
        }
    </script>

</body>
</html>
