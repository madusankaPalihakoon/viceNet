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
