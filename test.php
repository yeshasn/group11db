<?php
// dp.php - Database connection and functions
class DatabaseConnection {
    private $conn;
    
    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=localhost;dbname=GROUP11DB", "root", "");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    // Get employee data including skills, certifications, and experience
    public function getEmployeeData($employeeId) {
        try {
            // Get basic employee info
            $stmt = $this->conn->prepare("
                SELECT e.*, CONCAT(FName, ' ', LName) as FullName 
                FROM Employee e 
                WHERE EmployeeID = ?
            ");
            $stmt->execute([$employeeId]);
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Get skills
            $stmt = $this->conn->prepare("
                SELECT s.SkillName, s.SkillLevel 
                FROM Skill s 
                JOIN Employee_Skill es ON s.SkillID = es.SkillID 
                WHERE es.EmployeeID = ?
            ");
            $stmt->execute([$employeeId]);
            $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get certifications
            $stmt = $this->conn->prepare("
                SELECT c.CertificationName, c.IssuingOrg 
                FROM Certification c 
                JOIN Employee_Certification ec ON c.CertificationID = ec.CertificationID 
                WHERE ec.EmployeeID = ?
            ");
            $stmt->execute([$employeeId]);
            $certifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get experiences
            $stmt = $this->conn->prepare("
                SELECT e.RoleTitle, e.YearsOfExperience, e.Description 
                FROM Experience e 
                WHERE e.ExperienceID IN (
                    SELECT re.ExperienceID 
                    FROM Role_Experience re 
                    JOIN Role r ON re.RoleID = r.RoleID 
                    WHERE r.ProjectID IN (
                        SELECT ProjectID FROM Project WHERE ManagerID = ?
                    )
                )
            ");
            $stmt->execute([$employeeId]);
            $experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'employee' => $employee,
                'skills' => $skills,
                'certifications' => $certifications,
                'experiences' => $experiences
            ];
        } catch(PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    // Add new skill
    public function addSkill($employeeId, $skillName, $skillLevel) {
        try {
            $this->conn->beginTransaction();
            
            // Check if skill exists
            $stmt = $this->conn->prepare("
                SELECT SkillID FROM Skill 
                WHERE SkillName = ? AND SkillLevel = ?
            ");
            $stmt->execute([$skillName, $skillLevel]);
            $skill = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$skill) {
                // Create new skill
                $stmt = $this->conn->prepare("
                    INSERT INTO Skill (SkillName, SkillLevel) 
                    VALUES (?, ?)
                ");
                $stmt->execute([$skillName, $skillLevel]);
                $skillId = $this->conn->lastInsertId();
            } else {
                $skillId = $skill['SkillID'];
            }
            
            // Add employee-skill relationship
            $stmt = $this->conn->prepare("
                INSERT INTO Employee_Skill (EmployeeID, SkillID) 
                VALUES (?, ?)
            ");
            $stmt->execute([$employeeId, $skillId]);
            
            $this->conn->commit();
            return true;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    
    // Remove skill
    public function removeSkill($employeeId, $skillName, $skillLevel) {
        try {
            $stmt = $this->conn->prepare("
                DELETE FROM Employee_Skill 
                WHERE EmployeeID = ? AND SkillID = (
                    SELECT SkillID FROM Skill 
                    WHERE SkillName = ? AND SkillLevel = ?
                )
            ");
            $stmt->execute([$employeeId, $skillName, $skillLevel]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }
    
    // Similar functions for certifications and experience
    // Add certification
    public function addCertification($employeeId, $certName, $issuingOrg) {
        try {
            $this->conn->beginTransaction();
            
            $stmt = $this->conn->prepare("
                SELECT CertificationID FROM Certification 
                WHERE CertificationName = ? AND IssuingOrg = ?
            ");
            $stmt->execute([$certName, $issuingOrg]);
            $cert = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$cert) {
                $stmt = $this->conn->prepare("
                    INSERT INTO Certification (CertificationName, IssuingOrg) 
                    VALUES (?, ?)
                ");
                $stmt->execute([$certName, $issuingOrg]);
                $certId = $this->conn->lastInsertId();
            } else {
                $certId = $cert['CertificationID'];
            }
            
            $stmt = $this->conn->prepare("
                INSERT INTO Employee_Certification (EmployeeID, CertificationID) 
                VALUES (?, ?)
            ");
            $stmt->execute([$employeeId, $certId]);
            
            $this->conn->commit();
            return true;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    
    // Remove certification
    public function removeCertification($employeeId, $certName) {
        try {
            $stmt = $this->conn->prepare("
                DELETE FROM Employee_Certification 
                WHERE EmployeeID = ? AND CertificationID = (
                    SELECT CertificationID FROM Certification 
                    WHERE CertificationName = ?
                )
            ");
            $stmt->execute([$employeeId, $certName]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }
}

// API endpoint to handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new DatabaseConnection();
    $response = ['success' => false];
    
    $employeeId = $_POST['employeeId'] ?? null;
    $action = $_POST['action'] ?? '';
    $type = $_POST['type'] ?? '';
    
    if ($employeeId) {
        switch ($type) {
            case 'skill':
                if ($action === 'add') {
                    $success = $db->addSkill(
                        $employeeId,
                        $_POST['skillName'],
                        $_POST['skillLevel']
                    );
                } else {
                    $success = $db->removeSkill(
                        $employeeId,
                        $_POST['skillName'],
                        $_POST['skillLevel']
                    );
                }
                break;
                
            case 'certification':
                if ($action === 'add') {
                    $success = $db->addCertification(
                        $employeeId,
                        $_POST['certName'],
                        $_POST['issuingOrg']
                    );
                } else {
                    $success = $db->removeCertification(
                        $employeeId,
                        $_POST['certName']
                    );
                }
                break;
        }
        
        if ($success) {
            $response = [
                'success' => true,
                'data' => $db->getEmployeeData($employeeId)
            ];
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// If it's a GET request, return employee data
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $db = new DatabaseConnection();
    $data = $db->getEmployeeData($_GET['id']);
    
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>