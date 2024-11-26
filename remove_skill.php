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

$skillName = $_GET['skillName'];
$skillLevel = $_GET['skillLevel'];
$ecode = $_GET['ecode'];

$sqlSkillID = "SELECT SkillID FROM Skill WHERE SkillName = '$skillName' AND SkillLevel = '$skillLevel'";
$result = $conn->query($sqlSkillID);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $skillID = $row['SkillID'];

    $sqlDeleteEmployeeSkill = "DELETE FROM Employee_Skill WHERE SkillID = '$skillID' AND EmployeeID = '$ecode'";
    $conn->query($sqlDeleteEmployeeSkill);

    $sqlDeleteSkill = "DELETE FROM Skill WHERE SkillID = '$skillID'";
    $conn->query($sqlDeleteSkill);

}



$conn->close();
header("Location: employeehome.php?ecode=$ecode");

?>