<?php
$servername = "localhost";  // MySQL server address (localhost for XAMPP)
$username = "root";         // MySQL username (default is "root" for XAMPP)
$password = "";             // MySQL password (default is empty for XAMPP)
$dbname = "attendance_db";  // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
