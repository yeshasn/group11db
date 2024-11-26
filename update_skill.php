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

$sqlGetLastSkillID = "SELECT MAX(SkillID) AS lastSkillID FROM Skill";
$result = $conn->query($sqlGetLastSkillID);

if ($result->num_rows > 0) {
     $row = $result->fetch_assoc();
     $SkillID = $row['lastSkillID'] + 1;
     echo $SkillID;
}

// Check if the skill already exists in the Skill table
$sqlCheckSkill = "SELECT SkillID FROM Skill WHERE SkillName = '$skillName' AND SkillLevel = '$skillLevel';";
$result = $conn->query($sqlCheckSkill);


if ($result->num_rows > 0) {
     // Skill already exists, get the SkillID
     $row = $result->fetch_assoc();
     $skillID = $row['SkillID'];
     $sqlInsertEmployeeSkill = "INSERT INTO Employee_Skill (EmployeeID, SkillID) VALUES ('$ecode', '$skillID');";
     $conn->query($sqlInsertEmployeeSkill);
 } else {
     echo "Hello";
     $sqlInsertSkill = "INSERT INTO Skill (SkillID, SkillName, SkillLevel) VALUES ('$SkillID', '$skillName', '$skillLevel');";
     $conn->query($sqlInsertSkill);
     $sqlInsertEmployeeSkill = "INSERT INTO Employee_Skill (EmployeeID, SkillID) VALUES ('$ecode', '$SkillID');";
     $conn->query($sqlInsertEmployeeSkill);
}

$conn->close();
header("Location: employeehome.php?ecode=$ecode");

?>