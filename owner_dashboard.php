<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Owner Dashboard</title>
    <style>
        /* General Body Style */
        body {
            font-family: Arial, sans-serif;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        /* Header Styling */
        header {
            background-color:  palevioletred;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        /* Navigation Bar Styling */
        nav {
            margin-bottom: 20px;
        }

        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: space-around;
            background-color:  palevioletred;
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

        /* Form Container Styling */
        .dashboard-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
            background-color:  palevioletred;;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        /* Table Styling */
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
            cursor: pointer;
        }

        .action-btn.delete {
            background-color: #e74c3c;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 40px;
            text-align: right;
        }
    </style>
</head>
<body>
<div class="logo">
    <img src="landingpageimages/image-removebg-preview.png" alt="logo" width="160" height="130">
</div>
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

<!-- Properties Section -->
<div id="properties" class="form-section">
    <h2>Properties</h2>
    <form method="post" action="owner_dashboard.php" enctype="multipart/form-data">
        <label for="propertyName">Property Name:</label>
        <input type="text" id="propertyName" name="propertyName" required>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="numRooms">Number of Rooms:</label>
        <input type="number" id="numRooms" name="numRooms" required>

        <label for="propertyImage">Property Image:</label>
        <input type="file" id="propertyImage" name="propertyImage" accept="image/*" required>

        <input type="submit" name="addProperty" class="btn" value="Add Property">
    </form>
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
            </tr>
        </thead>
        <tbody>
            <?php while ($tenant = $tenantsResult->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $tenant['tenant_id']; ?></td>
                <td><?php echo $tenant['firstname']; ?></td>
                <td><?php echo $tenant['lastname']; ?></td>
                <td><?php echo $tenant['email']; ?></td>
                <td><?php echo $tenant['phonenumber']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Maintenance Requests Section -->
<div id="maintenance" class="form-section">
    <h2>Maintenance Requests</h2>
    <table>
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Details</th>
                <th>Status</th>
                <th>Request Date</th>
                <th>Tenant Name</th>
                <th>Property Name</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($request = $maintenanceResult->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $request['request_id']; ?></td>
                <td><?php echo $request['request_details']; ?></td>
                <td><?php echo $request['request_status']; ?></td>
                <td><?php echo $request['request_date']; ?></td>
                <td><?php echo $request['firstname'] . " " . $request['lastname']; ?></td>
                <td><?php echo $request['property_name']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Payments Tracking Section -->
<div id="payments" class="form-section">
    <h2>Payments</h2>
    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Amount Paid</th>
                <th>Payment Date</th>
                <th>Status</th>
                <th>Tenant Name</th>
                <th>Property Name</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($payment = $paymentsResult->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $payment['payment_id']; ?></td>
                <td><?php echo $payment['amount_paid']; ?></td>
                <td><?php echo $payment['payment_date']; ?></td>
                <td><?php echo $payment['payment_status']; ?></td>
                <td><?php echo $payment['firstname'] . " " . $payment['lastname']; ?></td>
                <td><?php echo $payment['property_name']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Messages Section -->
<div id="messages" class="form-section">
    <h2>Messages</h2>
    <table>
        <thead>
            <tr>
                <th>Message ID</th>
                <th>Content</th>
                <th>Type</th>
                <th>Date</th>
                <th>Tenant Name</th>
                <th>Property Name</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($message = $messagesResult->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $message['message_id']; ?></td>
                <td><?php echo $message['message_content']; ?></td>
                <td><?php echo $message['message_type']; ?></td>
                <td><?php echo $message['message_date']; ?></td>
                <td><?php echo $message['firstname'] . " " . $message['lastname']; ?></td>
                <td><?php echo $message['property_name']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
    // JavaScript to show and hide sections
    function showSection(section) {
        // Hide all sections
        const sections = document.querySelectorAll('.form-section');
        sections.forEach(function(section) {
            section.style.display = 'none';
        });

        // Show the selected section
        const selectedSection = document.getElementById(section);
        selectedSection.style.display = 'block';
    }
    
    // Default show the properties section
    showSection('properties');
</script>

</body>
</html>
