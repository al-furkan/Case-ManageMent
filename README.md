# Case-ManageMent
-- Table: courts
CREATE TABLE courts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    court_name VARCHAR(255) NOT NULL
);

-- Table: police_stations
CREATE TABLE police_stations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

-- Table: case_types
CREATE TABLE case_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

-- Table: cases
CREATE TABLE cases (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fileNo VARCHAR(255) NOT NULL,
    caseNo VARCHAR(255) NOT NULL,
    caseType INT,
    court INT,
    policeStation INT,
    date DATE,
    fixedFor DATE,
    firstParty VARCHAR(255),
    secondParty VARCHAR(255),
    mobileNo VARCHAR(15),
    appointedBy VARCHAR(255),
    lawSection TEXT,
    comments TEXT,
    status VARCHAR(50),
    FOREIGN KEY (caseType) REFERENCES case_types(id),
    FOREIGN KEY (court) REFERENCES courts(id),
    FOREIGN KEY (policeStation) REFERENCES police_stations(id)
);

-- Example INSERT statements
INSERT INTO courts (id, court_name) VALUES (1, 'Supreme Court');
INSERT INTO police_stations (id, name) VALUES (1, 'Downtown Police Station');
INSERT INTO case_types (id, name) VALUES (1, 'Civil Case');

INSERT INTO cases (
    id, fileNo, caseNo, caseType, court, policeStation, date, fixedFor,
    firstParty, secondParty, mobileNo, appointedBy, lawSection, comments, status
) VALUES (
    1, 'FN123', 'CN456', 1, 1, 1, '2025-01-01', '2025-02-01',
    'John Doe', 'Jane Smith', '1234567890', 'Attorney General',
    'Section 123A', 'No comments', 'Open'
);
# case-management
