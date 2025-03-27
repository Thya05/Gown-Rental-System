<?php
$servername = "localhost"; // Change if using a different server
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password (if any)
$database = "gown_rental"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
