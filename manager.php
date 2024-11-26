<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager</title>
    <link rel="stylesheet" href="manager.css"> <!-- Link to the external CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px; /* Ensures content starts at the top */
        }
        .container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto; /* Centers content */
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .dashboard {
            display: flex;
            gap: 15px;
            overflow-x: auto; /* Allows horizontal scrolling if needed */
            flex-wrap: nowrap; /* Keeps all panels in a single line */
        }
        .panel {
            flex: 1; /* Allows panels to stretch equally */
            min-width: 200px; /* Ensures panels do not shrink too small */
            background-color: #fafafa;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }
        .panel h3 {
            color: blue; /* Set text color to blue for the tabs */
            font-size: 1.1em;
            margin-bottom: 10px;
        }
        .icon {
            color: blue; /* Set the color for icons as blue */
            font-weight: bold;
            cursor: pointer;
        }
        .icon:hover {
            text-decoration: underline; /* Underline effect on hover */
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            align-self: flex-start; /* Aligns button to the start of the panel */
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .content {
            display: none; /* Keeps content hidden initially */
            margin-top: 10px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manager Dashboard</h2>
        <div class="dashboard">

            <!-- Dashboard Panel -->
            <div class="panel">
                <h3><span class="icon" onclick="togglePanel('dashboard-details')">DashBoard</span></h3>
                <div id="dashboard-details" class="content">
                    <p>Access all projects and view employee roles within each project.</p>
                </div>
                <h4>Projects</h4>
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
                    $projectQuery = "SELECT Project.ProjectName, Project.Description
                                FROM Project
                                WHERE Project.ManagerID = '$ecode'";
                    
                    $projectResult = $conn->query($projectQuery);

                    if ($projectResult && $projectResult->num_rows > 0) {
                        while ($projectRow = $projectResult->fetch_assoc()) {
                            $projectName = htmlspecialchars($projectRow['ProjectName']);
                            $description = htmlspecialchars($projectRow['Description']);
                            echo "<li>$projectName - $description</li>";
                        }
                    } else {
                        echo "<li>No projects found for this manager.</li>";
                    }

                    $conn->close();
                    ?>
                </ul>
            </div>

            <!-- Project Tab Panel -->
            <div class="panel">
                <h3><span class="icon" onclick="togglePanel('project-details')">Project Tab</span></h3>
                <div id="project-details" class="content">
                    <p>Select a project to view details, roles, and find matches.</p>

                    <!-- Project Dropdown -->
                    <label for="project-select">Choose a project:</label>
                    <select id="project-select" name="project" onchange="showProjectInfo()">
                        <option value="">--Select a project--</option>
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

                        // Query to get ProjectName for the manager using ecode
                        $projectQuery = "SELECT Project.ProjectName
                                        FROM Project
                                        WHERE Project.ManagerID = '$ecode'";
                        
                        $projectResult = $conn->query($projectQuery);
                        $counter = 1;

                        if ($projectResult && $projectResult->num_rows > 0) {
                            while ($projectRow = $projectResult->fetch_assoc()) {
                                $projectName = htmlspecialchars($projectRow['ProjectName']);
                                echo "<option value='project$counter' data-project-name='$projectName'>$projectName</option>";
                                $counter++;
                            }
                        } else {
                            echo "<option value=''>No projects found for this manager.</option>";
                        }

                        $conn->close();
                        ?>
                    </select>

                    <!-- Project Info Section -->
                    <div id="project-info" style="display: none; margin-top: 10px;">
                        <!-- Roles Required for Project -->
                        <h4>Roles for <span id="selected-project-name"></span></h4>
                        <ul id="roles-list">
                            <form action="employee_matchmaker.php" method="get">
                                <input type="hidden" name="selected_project_name" id="hidden-project-name">
                                <button type="submit">Employee Matchmaker</button>
                            </form>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Role Search Panel -->
            <div class="panel">
                <h3><span class="icon" onclick="navigateToEmployeeHome()">Employee Profile</span></h3>
            </div>

        </div>
    </div>

    <?php
$ecode = isset($_GET['ecode']) ? $_GET['ecode'] : ''; // Get ecode from URL
?>

    <script>
    function showProjectInfo() {
        const projectSelect = document.getElementById("project-select");
        const selectedOption = projectSelect.options[projectSelect.selectedIndex];
        const projectName = selectedOption.getAttribute("data-project-name");
        const projectInfo = document.getElementById("project-info");

        if (projectName) {
            // Update the project name in the Roles section
            document.getElementById("selected-project-name").innerText = projectName;
            projectInfo.style.display = "block";
            document.getElementById("hidden-project-name").value = projectName;
        } else {
            projectInfo.style.display = "none";
        }
    }

    function togglePanel(panelId) {
        const content = document.getElementById(panelId);
        content.style.display = content.style.display === "block" ? "none" : "block";
    }

    var ecode = "<?php echo $ecode; ?>";

    function navigateToEmployeeHome() {
        // Navigate to the employeehome.php page with ecode as a query parameter
        window.location.href = "employeehome.php?ecode=" + encodeURIComponent(ecode);
    }
    </script>
</body>
</html>