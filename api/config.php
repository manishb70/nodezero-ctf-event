<?php

$servername = "localhost";
$db_username = "root"; // Replace with your database username
$db_password = "";     // Replace with your database password
$dbname = "nodezero"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    // In a real application, you'd log this error.
    die("Connection failed: " . $conn->connect_error);
}



?>