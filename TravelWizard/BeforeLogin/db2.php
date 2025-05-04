<?php
$host = 'localhost';
$db = 'travelwizard1';
$user = 'root';
$pass = 'root';
// Character encoding for proper Unicode support
$charset = 'utf8mb4';

// Data Source Name string including host, database name, and charset
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options to enhance security and behavior
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return results as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use real prepared statements 
];

// Attempt to create a PDO instance 
try {
    // Establish connection
    $pdo = new PDO($dsn, $user, $pass, $options); 
} catch (\PDOException $e) {
    // If connection fails, throw an exception with the error message and code
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>

