-- Create Employee table first because other tables reference it
CREATE TABLE Employee (
    EmployeeID INT PRIMARY KEY,
    FName VARCHAR(50) NOT NULL,
    LName VARCHAR(50) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Department VARCHAR(50),
    ManagerID INT,
    DateHired DATE DEFAULT NULL,
    isManager BOOLEAN DEFAULT 0,
    FOREIGN KEY (ManagerID) REFERENCES Employee(EmployeeID) ON DELETE SET NULL
);

-- Create Project table next, as it is referenced by the Role table
CREATE TABLE Project (
    ProjectID INT PRIMARY KEY,
    ProjectName VARCHAR(100) DEFAULT NULL,
    Description TEXT DEFAULT NULL,
    ManagerID INT,  -- Remove NOT NULL here
    FOREIGN KEY (ManagerID) REFERENCES Employee(EmployeeID) ON DELETE SET NULL
);

-- Create Role table, which references Project
CREATE TABLE Role (
    RoleID INT PRIMARY KEY,
    RoleName VARCHAR(100) DEFAULT NULL,
    ProjectID INT,
    Description TEXT DEFAULT NULL,
    FOREIGN KEY (ProjectID) REFERENCES Project(ProjectID) ON DELETE SET NULL
);

-- Now you can create the other tables that reference the Employee and Project tables
CREATE TABLE Certification (
    CertificationID INT PRIMARY KEY,
    CertificationName VARCHAR(100) DEFAULT NULL,
    IssuingOrg VARCHAR(100) DEFAULT NULL
);

CREATE TABLE Employee_Certification (
    EmployeeID INT,
    CertificationID INT,
    PRIMARY KEY (EmployeeID, CertificationID),
    FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID) ON DELETE CASCADE,
    FOREIGN KEY (CertificationID) REFERENCES Certification(CertificationID) ON DELETE CASCADE
);

CREATE TABLE Skill (
    SkillID INT PRIMARY KEY,
    SkillName VARCHAR(100) NOT NULL,
    SkillLevel VARCHAR(50) NOT NULL
);

CREATE TABLE Employee_Skill (
    EmployeeID INT,
    SkillID INT,
    PRIMARY KEY (EmployeeID, SkillID),
    FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID) ON DELETE CASCADE,
    FOREIGN KEY (SkillID) REFERENCES Skill(SkillID) ON DELETE CASCADE
);

CREATE TABLE Employee_Experience (
    EmployeeID INT,
    ExperienceID INT,
    PRIMARY KEY (EmployeeID, ExperienceID),
    FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID) ON DELETE CASCADE,
    FOREIGN KEY (ExperienceID) REFERENCES Experience(ExperienceID) ON DELETE CASCADE
);

CREATE TABLE Experience (
    ExperienceID INT PRIMARY KEY,
    RoleTitle VARCHAR(100) NOT NULL,
    YearsOfExperience INT NOT NULL,
    Description TEXT DEFAULT NULL
);

CREATE TABLE Role_Skill (
    RoleID INT NOT NULL,  -- RoleID is required for primary key
    SkillID INT DEFAULT NULL,  -- SkillID can be NULL
    MinSkillLevel INT NOT NULL,
    PRIMARY KEY (RoleID, SkillID),  -- RoleID is the primary key
    FOREIGN KEY (RoleID) REFERENCES Role(RoleID) ON DELETE CASCADE,
    FOREIGN KEY (SkillID) REFERENCES Skill(SkillID) ON DELETE SET NULL
);

CREATE TABLE Role_Experience (
    RoleID INT,
    ExperienceID INT,
    MinYearsExperience INT NOT NULL,
    PRIMARY KEY (RoleID, ExperienceID),
    FOREIGN KEY (RoleID) REFERENCES Role(RoleID) ON DELETE CASCADE,
    FOREIGN KEY (ExperienceID) REFERENCES Experience(ExperienceID) ON DELETE CASCADE
);

CREATE TABLE Role_Certification (
    RoleID INT,
    CertificationID INT,
    PRIMARY KEY (RoleID, CertificationID),
    FOREIGN KEY (RoleID) REFERENCES Role(RoleID) ON DELETE CASCADE,
    FOREIGN KEY (CertificationID) REFERENCES Certification(CertificationID) ON DELETE CASCADE
);
