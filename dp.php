<?php
$servername = "localhost";
$username = "root"; // MySQL username
$password = "password"; // MySQL password (usually empty for local)
$dbname = "employee_matchmaker"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
