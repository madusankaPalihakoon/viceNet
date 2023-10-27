CREATE DATABASE vicenet;
USE vicenet;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    country VARCHAR(255),
    phone_number VARCHAR(20),
    birthday DATE,
    gender ENUM('Male', 'Female', 'Other'),
    password_hash VARCHAR(255) NOT NULL,
    is_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE email_verification (
    verification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    verification_code VARCHAR(255) NOT NULL,
    send_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

DELIMITER $$
CREATE TRIGGER addUserToEmailVerifications
AFTER INSERT ON users FOR EACH ROW
BEGIN
    INSERT INTO email_verifications (user_id, verification_code, send_time)
    VALUES (NEW.user_id, '', CURRENT_TIMESTAMP);
END $$
DELIMITER ;

CREATE TABLE user_profile (
    profile_id INT AUTO_INCREMENT,
    user_id INT,
    profile_pic VARCHAR(255),
    cover_pic VARCHAR(255),
    home VARCHAR(100),
    contact INT,
    education VARCHAR(255),
    employment VARCHAR(200),
    relationship_status VARCHAR(100),
    PRIMARY KEY (profile_id,user_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

DELIMITER $$
CREATE TRIGGER addUserToUserProfile
AFTER INSERT ON users FOR EACH ROW
BEGIN
    INSERT INTO user_profile (user_id)
    VALUES (NEW.user_id);
END $$
DELIMITER ;

UPDATE user_profile SET profile_pic = :profile_pic, cover_pic = :cover_pic, home = :home, contact = :contact, education = :education, employment = :employment, relationship_status = :relationship_status, profile_setup_status = :profile_setup_status WHERE user_id = :user_id;
