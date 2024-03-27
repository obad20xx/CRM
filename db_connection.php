<?php
error_reporting(0);
@ini_set('display_errors', 0);

$serverName = "127.0.0.1:3307"; // Or your MySQL server host
$username = "root"; // Your MySQL username
$password = "P@ssw0rd"; // Your MySQL password
$dbName = "queue"; // Your MySQL database name


// Establishes the connection
$conn = new mysqli($serverName, $username, $password, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>