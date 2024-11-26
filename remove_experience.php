<?php
$hostname = "127.0.0.1";
$username = "root"; // MySQL username
$password = "group11db"; // MySQL password (usually empty for local)
$dbname = "Group11DB"; // Your database name
$port = 3306;

// // Create connection
$conn = new mysqli($hostname, $username, $password, $dbname, $port);

// // Check connection
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
} else {
     echo "Connected successfully to the database";
}

$roleTitle = $_GET['roleTitle'];
$experienceYears = $_GET['experienceYears'];
$ecode = $_GET['ecode'];

$sqlExperienceID = "SELECT ExperienceID FROM Experience WHERE RoleTitle = '$roleTitle' AND YearsOfExperience = '$experienceYears'";
$result = $conn->query($sqlExperienceID);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $experienceID = $row['ExperienceID'];

    $sqlDeleteEmployeeExperience = "DELETE FROM Employee_Experience WHERE ExperienceID = '$experienceID' AND EmployeeID = '$ecode'";
    $conn->query($sqlDeleteEmployeeExperience);

    $sqlDeleteExperience = "DELETE FROM Experience WHERE ExperienceID = '$experienceID'";
    $conn->query($sqlDeleteExperience);

}


$conn->close();
header("Location: employeehome.php?ecode=$ecode");

?>