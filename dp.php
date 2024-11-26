<?php
$hostname = "127.0.0.1";
$username = "root"; // MySQL username
$password = "group11db"; // MySQL password (usually empty for local)
$dbname = "Group11DB"; // Your database name
$port = 3306;

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
        WHERE EmployeeID='$ecode' and FName='$fname' and LName='$lname'";

$result = $conn->query($sql);

$ecode = preg_replace('/\D/', '', $ecode);

if ($result) {
    if ($row = $result->fetch_assoc()) {
        $isManager = (int)$row["isManager"];

        // Redirect based on the isManager value
        if ($isManager === 1) {
            header("Location: manager.php?ecode=$ecode");
        } elseif ($isManager === 0) {
            header("Location: employeehome.php?ecode=$ecode");
        } else {
            echo "Error: Invalid employee status";
        }
    } else {
        echo "Error: Employee not found";
    }
    $result->free();
} else {
    echo "Error: Query failed";
}

$conn->close();
exit();

?>