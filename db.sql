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

-- UPDATE user_profile SET profile_pic = :profile_pic, cover_pic = :cover_pic, home = :home, contact = :contact, education = :education, employment = :employment, relationship_status = :relationship_status, profile_setup_status = :profile_setup_status WHERE user_id = :user_id;

CREATE TABLE posts (
    post_id INT AUTO_INCREMENT,
    user_id INT,
    post_img VARCHAR(200),
    post_caption VARCHAR(255),
    post_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (post_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE post_like (
    like_id INT AUTO_INCREMENT,
    post_id INT,
    liked_user INT,
    like_status TINYINT DEFAULT 0,
    PRIMARY KEY (like_id),
    UNIQUE KEY unique_like (post_id, liked_user),
    FOREIGN KEY (post_id) REFERENCES posts(post_id),
    FOREIGN KEY (liked_user) REFERENCES users(user_id)
);

CREATE TABLE like_count (
    like_count_id INT AUTO_INCREMENT,
    post_id INT,
    like_count INT,
    PRIMARY KEY (like_count_id),
    FOREIGN KEY (post_id) REFERENCES posts(post_id)
);

CREATE TABLE post_comment (
    comment_id INT AUTO_INCREMENT,
    post_id INT,
    comment_user INT,
    comment_text VARCHAR(255),
    PRIMARY KEY (comment_id),
    FOREIGN KEY (post_id) REFERENCES posts(post_id),
    FOREIGN KEY (comment_user) REFERENCES users(user_id)
);

-- Trigger to update like_count when a new like is inserted or updated
DELIMITER //
CREATE TRIGGER update_like_count
AFTER INSERT ON post_like
FOR EACH ROW
BEGIN
    DECLARE current_like_count INT;

    -- Get the current like count for the post
    SELECT COALESCE(like_count, 0) INTO current_like_count
    FROM like_count
    WHERE post_id = NEW.post_id;

    -- Update or insert into like_count
    IF current_like_count > 0 THEN
        UPDATE like_count
        SET like_count = current_like_count + 1
        WHERE post_id = NEW.post_id;
    ELSE
        INSERT INTO like_count (post_id, like_count)
        VALUES (NEW.post_id, 1);
    END IF;
END;
//
DELIMITER ;

-- SELECT post_id, COUNT(*) AS like_count
-- FROM post_like
-- WHERE like_status = 1
-- GROUP BY post_id;


