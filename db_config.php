<?php
// Database configuration
$host = "localhost"; // Change this if using a remote server
$username = "root";  // Change this to your actual database username
$password = "";      // Change this to your actual database password
$database = "ecommerce_db"; // Database name

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8");

?>
