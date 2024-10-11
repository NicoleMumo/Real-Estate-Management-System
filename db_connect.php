<?php
// Database connection details
$host = 'autorack.proxy.rlwy.net'; // Host from the Railway connection string
$db   = 'railway'; // Database name from the connection string
$user = 'root'; // Username from the connection string
$pass = 'LifLMGJMeLhzwTBqhtqGlzcdKozGdwFh'; // Password from the connection string
$port = '45514'; // Port from the Railway connection string

// Data Source Name (DSN) for the PDO connection
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

try {
    // Create a new PDO instance
    $pdo = new PDO($dsn, $user, $pass);
    // Set error mode to throw exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optional: Uncomment the line below for testing
    // echo "Database connection successful!"; 
} catch (PDOException $e) {
    // Output connection error
    echo "Connection failed: " . $e->getMessage();
}
?>
