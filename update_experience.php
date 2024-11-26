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
$yearsOfExperience = $_GET['yearsOfExperience'];
$description = $_GET['description'];
$ecode = $_GET['ecode'];

$sqlGetLastExperienceID = "SELECT MAX(ExperienceID) AS lastExperienceID FROM Experience";
$result = $conn->query($sqlGetLastExperienceID);

if ($result->num_rows > 0) {
     $row = $result->fetch_assoc();
     $ExperienceID = $row['lastExperienceID'] + 1;
     echo $ExperienceID;
}

// Check if the skill already exists in the Skill table
$sqlCheckExperience = "SELECT ExperienceID FROM Experience WHERE RoleTitle = '$roleTitle' AND YearsOfExperience = '$yearsOfExperience' AND Description = '$description';";
$result = $conn->query($sqlCheckExperience);


if ($result->num_rows > 0) {
     // Skill already exists, get the SkillID
     $row = $result->fetch_assoc();
     $experienceID = $row['ExperienceID'];
     $sqlInsertEmployeeExperience = "INSERT INTO Employee_Experience (EmployeeID, ExperienceID) VALUES ('$ecode', '$experienceID');";
     $conn->query($sqlInsertEmployeeExperience);
 } else {
     $sqlInsertExperience = "INSERT INTO Experience (ExperienceID, RoleTitle, YearsOfExperience, Description) VALUES ('$ExperienceID', '$roleTitle', '$yearsOfExperience', '$description');";
     $conn->query($sqlInsertExperience);
     $sqlInsertEmployeeExperience = "INSERT INTO Employee_Experience (EmployeeID, ExperienceID) VALUES ('$ecode', '$ExperienceID');";
     $conn->query($sqlInsertEmployeeExperience);
}

$conn->close();
header("Location: employeehome.php?ecode=$ecode");

?>