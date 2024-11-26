<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Matchmaker</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="profile-box">
            <h1>Employee Matchmaker</h1>
            <div class="matchmaker">
                <?php
                    // Database connection
                    $hostname = "127.0.0.1";
                    $username = "root";
                    $password = "group11db";
                    $dbname = "Group11DB";
                    $port = 3306;

                    // Create connection
                    $conn = new mysqli($hostname, $username, $password, $dbname, $port);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Get ecode from the URL
                    $projectName = isset($_GET['selected_project_name']) ? $_GET['selected_project_name'] : '';

                    // Escape the project name for safety
                    $projectName = $conn->real_escape_string($projectName);

                    // Query to get ProjectID from the Project table
                    $projectQuery = "SELECT ProjectID FROM Project WHERE ProjectName = '$projectName'";
                    $projectResult = $conn->query($projectQuery);

                    if ($projectResult && $projectResult->num_rows > 0) {
                        // Fetch the ProjectID
                        $projectRow = $projectResult->fetch_assoc();
                        $projectID = $projectRow['ProjectID'];

                        // Query to get all roles for the given ProjectID
                        $roleQuery = "SELECT RoleID, RoleName FROM Role WHERE ProjectID = '$projectID'";
                        $roleResult = $conn->query($roleQuery);

                        if ($roleResult && $roleResult->num_rows > 0) {
                            echo "<h2>Roles for Project: " . htmlspecialchars($projectName) . "</h2>";

                            while ($roleRow = $roleResult->fetch_assoc()) {
                                $roleID = $roleRow['RoleID'];
                                $roleName = htmlspecialchars($roleRow['RoleName']);

                                echo "<h3>Role: $roleName</h3>";
                                
                                // Query to get SkillIDs for the given RoleID
                                $skillQuery = "SELECT SkillID FROM Role_Skill WHERE RoleID = '$roleID'";
                                $skillResult = $conn->query($skillQuery);

                                if ($skillResult && $skillResult->num_rows > 0) {
                                    echo "<ul>";

                                    while ($skillRow = $skillResult->fetch_assoc()) {
                                        $skillID = $skillRow['SkillID'];

                                        $employeeIDsQuery = "
                                        SELECT EmployeeID
                                        FROM Employee_Skill
                                        WHERE SkillID = '$skillID'
                                    ";
                                    $employeeIDsResult = $conn->query($employeeIDsQuery);
                                    
                                    if ($employeeIDsResult && $employeeIDsResult->num_rows > 0) {
                                    
                                        while ($employeeIDRow = $employeeIDsResult->fetch_assoc()) {
                                            $employeeID = $employeeIDRow['EmployeeID'];
                                    
                                            // Query to get EmployeeName from Employee table using the EmployeeID
                                            $employeeNameQuery = "
                                                SELECT FName, LName
                                                FROM Employee
                                                WHERE EmployeeID = '$employeeID'
                                            ";
                                            $employeeNameResult = $conn->query($employeeNameQuery);
                                    
                                            if ($employeeNameResult && $employeeNameResult->num_rows > 0) {
                                                $employeeNameRow = $employeeNameResult->fetch_assoc();
                                                $employeeFName = htmlspecialchars($employeeNameRow['FName']);
                                                $employeeLName = htmlspecialchars($employeeNameRow['LName']);
                                    
                                                echo "<li>$employeeFName $employeeLName</li>";
                                            } else {
                                                echo "<li>Employee with ID $employeeID - Name not found</li>";
                                            }
                                        }
                                    
                                        echo "</ul></li>";
                                    } else {
                                        echo "<li>No matching employees found</li>";
                                    }
                                    }

                                    echo "</ul>";
                                } else {
                                    echo "<p>No skills found for this role.</p>";
                                }
                      }
                        } else {
                            echo "<p>No roles found for this project.</p>";
                        }
                    } else {
                        echo "<p>Project not found.</p>";
                    }

                    $conn->close();

                ?>
            </div>
        </div>
    </div>
</body>