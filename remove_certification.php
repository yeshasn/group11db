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

$sqlCertificationID = "SELECT CertificationID FROM Certification WHERE CertificationName = '$certificationName' AND IssuingOrg = '$issuingOrg'";
$result = $conn->query($sqlCertificationID);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $certificationID = $row['CertificationID'];

    $sqlDeleteEmployeeCertification = "DELETE FROM Employee_Certification WHERE CertificationID = '$certificationID' AND EmployeeID = '$ecode'";
    $conn->query($sqlDeleteEmployeeCertification);

    $sqlDeleteCertification = "DELETE FROM Certification WHERE CertificationID = '$certificationID'";
    $conn->query($sqlDeleteCertification);

}



$conn->close();
header("Location: employeehome.php?ecode=$ecode");

?>