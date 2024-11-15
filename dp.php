<?php
header("Content-Type: application/json");

$hostname = "127.0.0.1";
$username = "root"; // MySQL username
$password = "group11db"; // MySQL password (usually empty for local)
$dbname = "Group11DB"; // Your database name
$port = 3306;

$response = [];

echo "Hello";

// // Create connection
$conn = new mysqli($hostname, $username, $password, $dbname, $port);

// // Check connection
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
} else {
     echo "Connected successfully to the database";
}

$fname = $_GET['fname'];
$lname = $_GET['lname'];
$ecode = $_GET['ecode'];

$sql = "SELECT isManager
        FROM Employee
        WHERE FName='$fname' and LName='$lname' and EmployeeID='$ecode';";

$result = $conn->query($sql);

if ($result) {
    while($row = $result->fetch_assoc()) {
        printf("IsManager: %s", $row["isManager"]);
        $response["isManager"] = $row["isManager"];
    }
    $result->free();
}

$conn->close();

echo json_encode($response);

?>
