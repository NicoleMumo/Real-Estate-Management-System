<?php
// Database connection details
$host = 'junction.proxy.rlwy.net';
$db   = 'railway';
$user = 'root';
$pass = 'SzKOkadHNRkfbpZIdFxLAHMUXfJrkOxf'; // Password from the Railway connection string
$port = '36546'; // Railway port

// Data Source Name (DSN) for the PDO connection
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

try {
    // Create a new PDO instance
    $pdo = new PDO($dsn, $user, $pass);
    // Set error mode to throw exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful!"; // Test message
} catch (PDOException $e) {
    // Output connection error
    echo "Connection failed: " . $e->getMessage();
}
?>
