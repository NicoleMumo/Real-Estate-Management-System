<?php
// Include the database connection
include 'db_connect.php';

// Assuming $userId represents the logged-in property owner’s ID
$userId = 1; // Replace with the actual logged-in user ID

// Handle form submission for adding properties
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addProperty"])) {
    $propertyName = trim($_POST["propertyName"]);
    $location = trim($_POST["location"]);
    $price = floatval($_POST["price"]);
    $numRooms = intval($_POST["numRooms"]);

    // Insert property into the database
    $stmt = $conn->prepare("INSERT INTO Properties (property_name, location, price, num_rooms, owner_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdii", $propertyName, $location, $price, $numRooms, $userId);

    if ($stmt->execute()) {
        echo "<script>alert('New property added successfully');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch tenants data for the property owner
$tenantsSql = "
    SELECT t.tenant_id, t.firstname, t.lastname, t.email, t.phonenumber
    FROM tenants t
    JOIN properties p ON t.property_id = p.property_id
    WHERE p.owner_id = ?";
$tenantsStmt = $conn->prepare($tenantsSql);
$tenantsStmt->bind_param("i", $userId);
$tenantsStmt->execute();
$tenantsResult = $tenantsStmt->get_result();

// Fetch maintenance requests
$maintenanceSql = "
    SELECT mr.request_id, mr.request_details, mr.request_status, mr.request_date,
           t.firstname, t.lastname, p.property_name
    FROM maintenance_requests mr
    JOIN tenants t ON mr.tenant_id = t.tenant_id
    JOIN properties p ON mr.property_id = p.property_id
    WHERE p.owner_id = ?";
$maintenanceStmt = $conn->prepare($maintenanceSql);
$maintenanceStmt->bind_param("i", $userId);
$maintenanceStmt->execute();
$maintenanceResult = $maintenanceStmt->get_result();

// Fetch payments data
$paymentsSql = "
    SELECT pay.payment_id, pay.amount_paid, pay.payment_date, pay.payment_status,
           t.firstname, t.lastname, p.property_name
    FROM payments pay
    JOIN tenants t ON pay.tenant_id = t.tenant_id
    JOIN properties p ON t.property_id = p.property_id
    WHERE p.owner_id = ?";
$paymentsStmt = $conn->prepare($paymentsSql);
$paymentsStmt->bind_param("i", $userId);
$paymentsStmt->execute();
$paymentsResult = $paymentsStmt->get_result();

// Fetch messages for the property owner
$messagesSql = "
    SELECT m.message_id, m.message_content, m.message_type, m.message_date, 
           t.firstname, t.lastname, p.property_name
    FROM messages m
    JOIN properties p ON m.property_id = p.property_id
    JOIN tenants t ON m.tenant_id = t.tenant_id
    WHERE p.owner_id = ?
    ORDER BY m.message_date DESC";
$messagesStmt = $conn->prepare($messagesSql);
$messagesStmt->bind_param("i", $userId);
$messagesStmt->execute();
$messagesResult = $messagesStmt->get_result();

?>
<script>
// Populate data dynamically in the HTML sections
document.addEventListener("DOMContentLoaded", function() {
    // Populate Tenants table
    const tenantsTable = document.getElementById("tenantTable");
    <?php while ($tenant = $tenantsResult->fetch_assoc()): ?>
    tenantsTable.innerHTML += `
        <tr>
            <td><?php echo $tenant['tenant_id']; ?></td>
            <td><?php echo $tenant['firstname']; ?></td>
            <td><?php echo $tenant['lastname']; ?></td>
            <td><?php echo $tenant['email']; ?></td>
            <td><?php
