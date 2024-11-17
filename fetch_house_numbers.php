<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db_connect.php'; // Ensure this file contains the correct database connection details

header('Content-Type: application/json');

try {
    $query = "SELECT property_id FROM properties";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $properties = [];
    while ($row = $result->fetch_assoc()) {
        $properties[] = [
            'id' => $row['property_id'],
            'house_number' => $row['property_id'], // Using property_id as the dropdown value
        ];
    }

    echo json_encode($properties);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
