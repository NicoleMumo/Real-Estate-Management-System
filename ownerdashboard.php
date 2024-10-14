<?php
// Database connection
$servername = "localhost"; // assuming you're running MySQL on localhost
$username = "root"; // your MySQL username
$password = ""; // your MySQL password
$dbname = "software"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding properties
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addProperty"])) {
    $propertyName = $_POST["propertyName"];
    $location = $_POST["location"];
    $price = $_POST["price"];
    $numRooms = $_POST["numRooms"];

    $sql = "INSERT INTO properties (property_name, location, price, num_rooms)
            VALUES ('$propertyName', '$location', '$price', '$numRooms')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New property added successfully');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Owner Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        header {
            background-color: #3498db;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        nav {
            margin-bottom: 20px;
        }
        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: space-around;
            background-color: #2c3e50;
        }
        nav ul li {
            display: inline;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 10px;
            display: block;
        }
        nav ul li a:hover {
            background-color: #2980b9;
        }
        .dashboard-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .form-section {
            margin-bottom: 20px;
            display: none;
        }
        label, input, textarea, table, .btn {
            margin-bottom: 10px;
            display: block;
            width: 100%;
        }
        .btn {
            background-color: #3498db;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .action-btn {
            background-color: #2ecc71;
            padding: 5px;
            color: white;
            border: none;
        }
        .action-btn.delete {
            background-color: #e74c3c;
        }
    </style>
</head>
<body>
    <header>
        <h1>Property Owner Dashboard</h1>
    </header>

    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="#" onclick="showSection('properties')">Properties</a></li>
            <li><a href="#" onclick="showSection('tenants')">Tenants Management</a></li>
            <li><a href="#" onclick="showSection('maintenance')">Maintenance Requests</a></li>
            <li><a href="#" onclick="showSection('payments')">Payment Tracking</a></li>
            <li><a href="#" onclick="showSection('messages')">Messages/Notifications</a></li>
        </ul>
    </nav>

    <!-- Dashboard Form -->
    <form class="dashboard-form" method="post" action="">
        <!-- Properties Section -->
        <div id="properties" class="form-section">
            <h2>Properties</h2>
            <label for="propertyName">Property Name:</label>
            <input type="text" id="propertyName" name="propertyName" required>
            
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>
            
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required>
            
            <label for="numRooms">Number of Rooms:</label>
            <input type="number" id="numRooms" name="numRooms" required>
            
            <button type="submit" class="btn" name="addProperty">Add Property</button>
        </div>

        <!-- Tenants Management Section -->
        <div id="tenants" class="form-section">
            <h2>Tenants Management</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tenant ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>John</td>
                        <td>Doe</td>
                        <td>john@example.com</td>
                        <td>123-456-7890</td>
                        <td>
                            <button class="action-btn">Edit</button>
                            <button class="action-btn delete">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Maintenance Requests Section -->
        <div id="maintenance" class="form-section">
            <h2>Maintenance Requests</h2>
            <p>Manage maintenance requests from tenants.</p>
            <button type="button" class="btn">View Requests</button>
            <button type="button" class="btn">Add New Request</button>
        </div>

        <!-- Payment Tracking Section -->
        <div id="payments" class="form-section">
            <h2>Payment Tracking</h2>
            <p>View payment history, upcoming payments, and outstanding rent balances.</p>
            <button type="button" class="btn">View Payments</button>
        </div>

        <!-- Messages/Notifications Section -->
        <div id="messages" class="form-section">
            <h2>Messages/Notifications</h2>
            <p>Check important updates such as maintenance alerts, tenant messages, and payment reminders.</p>
            <button type="button" class="btn">View Messages</button>
        </div>
    </form>

    <script>
        // Function to show the selected section
        function showSection(sectionId) {
            // Hide all sections
            var sections = document.querySelectorAll('.form-section');
            sections.forEach(function(section) {
                section.style.display = 'none';
            });

            // Show the selected section
            document.getElementById(sectionId).style.display = 'block';
        }

        // Show the Properties section by default
        showSection('properties');
    </script>
</body>
</html>
