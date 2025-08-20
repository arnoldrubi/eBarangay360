-- eBarangay360 Full SQL Schema (Production-Ready with InnoDB)

-- USERS TABLE
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'secretary', 'health_officer', 'blotter_officer', 'zone_leader', 'resident') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- RESIDENTS TABLE
CREATE TABLE residents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    first_name VARCHAR(255),
    middle_name VARCHAR(255),
    last_name VARCHAR(255),
    date_of_birth DATE,
    place_of_birth_province VARCHAR(255),
    gender VARCHAR(10),
    civil_status VARCHAR(50),
    photo_filename VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_residents_users FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

-- CERTIFICATES TABLE
CREATE TABLE certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resident_id INT NOT NULL,
    certificate_type ENUM('residency', 'indigency', 'clearance', 'permit') NOT NULL,
    purpose VARCHAR(255),
    issue_date DATE DEFAULT CURRENT_DATE,
    status ENUM('pending', 'approved', 'denied') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_certificates_resident FOREIGN KEY (resident_id) REFERENCES residents(id)
) ENGINE=InnoDB;

-- BLOTTERS TABLE
CREATE TABLE blotters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complainant_id INT,
    defendant_id INT,
    case_type VARCHAR(255),
    description TEXT,
    status ENUM('open', 'settled', 'unsettled', 'certified') DEFAULT 'open',
    hearing_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_blotter_complainant FOREIGN KEY (complainant_id) REFERENCES residents(id),
    CONSTRAINT fk_blotter_defendant FOREIGN KEY (defendant_id) REFERENCES residents(id)
) ENGINE=InnoDB;

-- HEALTH RECORDS TABLE
CREATE TABLE health_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resident_id INT,
    record_type ENUM('immunization', 'prenatal', 'postnatal') NOT NULL,
    record_date DATE NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_health_resident FOREIGN KEY (resident_id) REFERENCES residents(id)
) ENGINE=InnoDB;

-- VISITOR LOG TABLE
CREATE TABLE visitor_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visitor_name VARCHAR(255),
    visit_date DATE DEFAULT CURRENT_DATE,
    purpose VARCHAR(255),
    qr_code VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ANNOUNCEMENTS TABLE
CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    banner_filename VARCHAR(255),
    post_date DATE DEFAULT CURRENT_DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
