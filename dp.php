<?php
$hostname = "127.0.0.1";
$username = "root"; // MySQL username
$password = "group11db"; // MySQL password (usually empty for local)
$dbname = "Group11DB"; // Your database name
$port = 3306;

echo "Hello";

// Create connection
$conn = new mysqli($hostname, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully to the database";
}
?>
