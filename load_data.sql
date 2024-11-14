ALTER TABLE Employee
DROP FOREIGN KEY employee_ibfk_1;

LOAD DATA INFILE '/Users/sujoebose/hephzi/UTDCS4347/group11db/employee.csv'
INTO TABLE Employee
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/sujoebose/hephzi/UTDCS4347/group11db/certification.csv'
INTO TABLE Certification
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/sujoebose/hephzi/UTDCS4347/group11db/employee_certification.csv'
INTO TABLE Employee_Certification
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/sujoebose/hephzi/UTDCS4347/group11db/skill.csv'
INTO TABLE Skill
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/sujoebose/hephzi/UTDCS4347/group11db/employee_skill.csv'
INTO TABLE Employee_Skill
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/sujoebose/hephzi/UTDCS4347/group11db/experience.csv'
INTO TABLE Experience
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/sujoebose/hephzi/UTDCS4347/group11db/role.csv'
INTO TABLE Role
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/sujoebose/hephzi/UTDCS4347/group11db/project.csv'
INTO TABLE Project
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/sujoebose/hephzi/UTDCS4347/group11db/role_skill.csv'
INTO TABLE Role_Skill
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/sujoebose/hephzi/UTDCS4347/group11db/role_experience.csv'
INTO TABLE Role_Experience
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

ALTER TABLE Employee
ADD CONSTRAINT employee_ibfk_1
FOREIGN KEY (ManagerID) REFERENCES Employee(EmployeeID) ON DELETE SET NULL;

