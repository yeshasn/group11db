<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="profile-box">
            <h1>Employee Profile</h1>
            <div class="profile-info">
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
                    $ecode = isset($_GET['ecode']) ? $_GET['ecode'] : '';

                    $sql = "SELECT FName, LName FROM Employee WHERE EmployeeID = '$ecode'";
                    $result = $conn->query($sql);

                    if ($result && $row = $result->fetch_assoc()) {
                        // Display Name and Role
                        $fullName = $row["FName"] . " " . $row["LName"];
                        echo "<p><strong>Name:</strong> " . htmlspecialchars($fullName) . "</p>";
                        echo "<p><strong>Employee ID:</strong> " . htmlspecialchars($ecode) . "</p>";
                    } else {
                        echo "<p>Error: Employee not found</p>";
                    }

                    $conn->close();

                ?>
            </div>
                <h2>Update Profile</h2>

                <!-- Skills Section -->
                <div class="section">
                    <h3>Skills</h3>
                    <form action="update_skill.php" method="get">
                        <div class="input-group">
                            <input type="hidden" name="ecode" value="<?php echo htmlspecialchars($_GET['ecode']); ?>">
                            <input type="text" name="skillName" placeholder="Skill name">
                            <input type="text" name="skillLevel" placeholder="Skill level (e.g., Beginner, Intermediate)">
                            <button type="submit" class="add-btn">Add</button>
                        </div>
                    </form>
                    <ul class="list">
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
                        $ecode = isset($_GET['ecode']) ? $_GET['ecode'] : '';

                        // Combined query to get SkillName and SkillLevel for the employee using ecode
                        $skillQuery = "SELECT Skill.SkillName, Skill.SkillLevel 
                                    FROM Employee_Skill
                                    JOIN Skill ON Employee_Skill.SkillID = Skill.SkillID
                                    WHERE Employee_Skill.EmployeeID = '$ecode'";
                        
                        $skillResult = $conn->query($skillQuery);

                        if ($skillResult && $skillResult->num_rows > 0) {
                            while ($skillRow = $skillResult->fetch_assoc()) {
                                $skillName = htmlspecialchars($skillRow['SkillName']);
                                $skillLevel = htmlspecialchars($skillRow['SkillLevel']);
                                echo "<li>
                                $skillName ($skillLevel) 
                                <form action='remove_skill.php' method='get'>
                                    <input type='hidden' name='ecode' value='$ecode'>
                                    <input type='hidden' name='skillName' value='$skillName'>
                                    <input type='hidden' name='skillLevel' value='$skillLevel'>
                                    <button type='submit'>Remove</button>
                                </form>
                              </li>";
                            }
                        } else {
                            echo "<li>No skills found for this employee.</li>";
                        }

                        $conn->close();
                        ?>
                    </ul>
                </div>

                <!-- Experience Section -->
                <div class="section">
                    <h3>Experience</h3>
                    <form action="update_experience.php" method="get">
                        <div class="input-group">
                            <input type="hidden" name="ecode" value="<?php echo htmlspecialchars($_GET['ecode']); ?>">
                            <input type="text" name="roleTitle" placeholder="Role title">
                            <input type="text" name="yearsOfExperience" placeholder="Years of experience">
                            <input type="text" name="description" placeholder="Brief description">
                            <button type="submit" class="add-btn">Add</button>
                        </div>
                    </form>
                    <ul class="list">
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
                        $ecode = isset($_GET['ecode']) ? $_GET['ecode'] : '';

                        // Combined query to get SkillName and SkillLevel for the employee using ecode
                        $experienceQuery = "SELECT Experience.RoleTitle, Experience.YearsOfExperience
                                    FROM Employee_Experience
                                    JOIN Experience ON Employee_Experience.ExperienceID = Experience.ExperienceID
                                    WHERE Employee_Experience.EmployeeID = '$ecode'";
                        
                        $experienceResult = $conn->query($experienceQuery);

                        if ($experienceResult && $experienceResult->num_rows > 0) {
                            while ($experienceRow = $experienceResult->fetch_assoc()) {
                                $roleTitle = htmlspecialchars($experienceRow['RoleTitle']);
                                $experienceYears = htmlspecialchars($experienceRow['YearsOfExperience']);
                                echo "<li>
                                $roleTitle ($experienceYears years)
                                <form action='remove_experience.php' method='get'>
                                    <input type='hidden' name='ecode' value='$ecode'>
                                    <input type='hidden' name='roleTitle' value='$roleTitle'>
                                    <input type='hidden' name='experienceYears' value='$experienceYears'>
                                    <button type='submit'>Remove</button>
                                </form>
                              </li>";
                            }
                        } else {
                            echo "<li>No experience found for this employee.</li>";
                        }

                        $conn->close();
                        ?>
                    </ul>
                </div>

                <!-- Certifications Section -->
                <div class="section">
                    <h3>Certifications</h3>
                    <form action="update_certification.php" method="get">
                        <div class="input-group">
                            <input type="hidden" name="ecode" value="<?php echo htmlspecialchars($_GET['ecode']); ?>">
                            <input type="text" name="certificationName" placeholder="Certification name">
                            <input type="text" name="issuingOrg" placeholder="Issuing Organization">
                            <button type="submit" class="add-btn">Add</button>
                        </div>
                    </form>
                    <ul class="list">
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
                        $ecode = isset($_GET['ecode']) ? $_GET['ecode'] : '';

                        // Combined query to get SkillName and SkillLevel for the employee using ecode
                        $certificationQuery = "SELECT Certification.CertificationName, Certification.IssuingOrg
                                    FROM Employee_Certification
                                    JOIN Certification ON Employee_Certification.CertificationID = Certification.CertificationID
                                    WHERE Employee_Certification.EmployeeID = '$ecode'";
                        
                        $certificationResult = $conn->query($certificationQuery);

                        if ($certificationResult && $certificationResult->num_rows > 0) {
                            while ($certificationRow = $certificationResult->fetch_assoc()) {
                                $certificationName = htmlspecialchars($certificationRow['CertificationName']);
                                $issuingOrg = htmlspecialchars($certificationRow['IssuingOrg']);
                                echo "<li>
                                $certificationName by $issuingOrg
                                <form action='remove_certification.php' method='get'>
                                    <input type='hidden' name='ecode' value='$ecode'>
                                    <input type='hidden' name='certificationName' value='$certificationName'>
                                    <input type='hidden' name='issuingOrg' value='$issuingOrg'>
                                    <button type='submit'>Remove</button>
                                </form>
                              </li>";
                            }
                        } else {
                            echo "<li>No certifications found for this employee.</li>";
                        }

                        $conn->close();
                        ?>
                    </ul>
                </div>
            </form>
        </div>
        <div class="side-panel"></div>
    </div>
</body>
</html>