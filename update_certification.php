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

$certificationName = $_GET['certificationName'];
$issuingOrg = $_GET['issuingOrg'];
$ecode = $_GET['ecode'];

$sqlGetLastCertificationID = "SELECT MAX(CertificationID) AS lastCertificationID FROM Certification";
$result = $conn->query($sqlGetLastCertificationID);

if ($result->num_rows > 0) {
     $row = $result->fetch_assoc();
     $CertificationID = $row['lastCertificationID'] + 1;
     echo $CertificationID;
}

// Check if the skill already exists in the Skill table
$sqlCheckCertification = "SELECT CertificationID FROM Certification WHERE CertificationName = '$certificationName' AND IssuingOrg = '$issuingOrg';";
$result = $conn->query($sqlCheckCertification);


if ($result->num_rows > 0) {
     // Skill already exists, get the SkillID
     $row = $result->fetch_assoc();
     $certificationID = $row['CertificationID'];
     $sqlInsertEmployeeCertification = "INSERT INTO Employee_Certification (EmployeeID, CertificationID) VALUES ('$ecode', '$certificationID');";
     $conn->query($sqlInsertEmployeeCertification);
 } else {
     echo "Hello";
     $sqlInsertCertification = "INSERT INTO Certification (CertificationID, CertificationName, IssuingOrg) VALUES ('$CertificationID', '$certificationName', '$issuingOrg');";
     $conn->query($sqlInsertCertification);
     $sqlInsertEmployeeCertification = "INSERT INTO Employee_Certification (EmployeeID, CertificationID) VALUES ('$ecode', '$CertificationID');";
     $conn->query($sqlInsertEmployeeCertification);
}

$conn->close();
header("Location: employeehome.php?ecode=$ecode");

?>