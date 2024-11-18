<?php
session_start();

if (!isset($_SESSION['tenant_id']) || !isset($_SESSION['property_id'])) {
    echo "Error: Tenant or Property not logged in.";
    exit();
}

$tenantId = $_SESSION['tenant_id'];
$propertyId = $_SESSION['property_id'];

$host = 'localhost';
$db = 'software';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare(
    "SELECT description, priority, status, created_at, image_path 
     FROM maintenance_requests 
     WHERE tenant_id = ? AND property_id = ?"
);
$stmt->bind_param("ii", $tenantId, $propertyId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>
            <strong>Description:</strong> {$row['description']}<br>
            <strong>Priority:</strong> {$row['priority']}<br>
            <strong>Status:</strong> {$row['status']}<br>
            <strong>Created At:</strong> {$row['created_at']}<br>";
        if ($row['image_path']) {
            echo "<img src='{$row['image_path']}' alt='Request Image' style='max-width: 100%; height: auto;'><br>";
        }
        echo "</li><hr>";
    }
    echo "</ul>";
} else {
    echo "No maintenance requests found.";
}

$stmt->close();
$conn->close();
?>
