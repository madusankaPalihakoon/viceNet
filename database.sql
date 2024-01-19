CREATE TABLE users (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL UNIQUE,
    username VARCHAR(200) NOT NULL UNIQUE,
    country VARCHAR(200),
    phone VARCHAR(50),
    birthday DATE,
    gender ENUM('Male', 'Female', 'Other'),
    passwordHash VARCHAR(255) NOT NULL,
    verificationState TINYINT(1) DEFAULT 0,
    sessionToken VARCHAR(255),
    createdTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email(100)),
    INDEX idx_username (username(100))
);

CREATE TABLE verification (
    verificationID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    verificationCodeHash VARCHAR(255) DEFAULT NULL,
    salt VARCHAR(100) DEFAULT NULL,
    AttemptsCount TINYINT(1) DEFAULT 0,
    sentTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES users(userID)
);

DELIMITER //
CREATE TRIGGER AddUserToVerification
AFTER INSERT
ON users FOR EACH ROW
BEGIN
    INSERT INTO Verification (userID, verificationCodeHash,salt,AttemptsCount,sentTime)
    VALUES (NEW.userID, '','','',CURRENT_TIMESTAMP);
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER UpdateSentTimeOnUpdateVerificationCode
BEFORE UPDATE
ON verification FOR EACH ROW
BEGIN
    IF NEW.verificationCodeHash <> OLD.verificationCodeHash OR NEW.salt <> OLD.salt THEN
        SET NEW.sentTime = CURRENT_TIMESTAMP;
    END IF;
END;
//
DELIMITER ;


CREATE TABLE ContactInfo (
    contactID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    contactType VARCHAR(50),
    contactValue VARCHAR(255),
    FOREIGN KEY (userID) REFERENCES users(userID)
);

CREATE TABLE Profile (
    profileID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT UNIQUE,
    profilePic VARCHAR(255),
    coverPic VARCHAR(255),
    home VARCHAR(100),
    birthday DATE,
    relationship VARCHAR(100),
    mobile INT,
    profileStatus TINYINT(1) DEFAULT 0,
    FOREIGN KEY (userID) REFERENCES users(userID)
);

DELIMITER //

CREATE TRIGGER AddUserToContactInfoAndProfile
AFTER INSERT
ON users FOR EACH ROW
BEGIN
    -- Insert into ContactInfo table
    INSERT INTO ContactInfo (userID, contactType, contactValue)
    VALUES (NEW.userID, 'email', NEW.email);

    -- Insert into Profile table
    INSERT INTO Profile (userID, profilePic, coverPic, home, birthday, relationship, mobile, profileStatus)
    VALUES (NEW.userID, '', '', '', '', '', '', 0);
END;

//
DELIMITER ;

CREATE TABLE friends (
    friendship_id INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    friend_id INT,
    FOREIGN KEY (userID) REFERENCES Profile(userID),
    FOREIGN KEY (friend_id) REFERENCES Profile(userID),
    UNIQUE KEY unique_friendship (userID, friend_id)
);


CREATE TABLE Post (
    postID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    postText TEXT,
    postImg VARCHAR(200),
    postTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES users(userID)
);

CREATE TABLE Story (
    storyID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    storyImg VARCHAR(200),
    storyCaption VARCHAR(200),
    storyTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES users(userID)
);

CREATE TABLE Likes (
    likeID INT AUTO_INCREMENT PRIMARY KEY,
    postID INT,
    userID INT,
    likeStatus TINYINT DEFAULT 0,
    UNIQUE KEY unique_like (postID, userID),
    FOREIGN KEY (postID) REFERENCES Post(postID),
    FOREIGN KEY (userID) REFERENCES users(userID)
);

CREATE TABLE LikeCount (
    LikeCountID INT AUTO_INCREMENT PRIMARY KEY,
    postID INT,
    totalLikeCount INT,
    FOREIGN KEY (postID) REFERENCES Post(postID)
);

CREATE TABLE Comments (
    CommentsID INT AUTO_INCREMENT PRIMARY KEY,
    postID INT,
    userID INT,
    CommentText VARCHAR(255),
    commentTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (postID) REFERENCES Post(postID),
    FOREIGN KEY (userID) REFERENCES users(userID)
);

CREATE TABLE Images(
    ImgID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    Img VARCHAR(200),
    FOREIGN KEY (userID) REFERENCES users(userID)
);

DELIMITER //

CREATE TRIGGER AddImageOnProfileInsert
AFTER INSERT
ON Profile FOR EACH ROW
BEGIN
    -- Insert into Images table for profilePic
    IF NEW.profilePic IS NOT NULL AND NEW.profilePic != '' THEN
        INSERT INTO Images (userID, Img)
        VALUES (NEW.userID, NEW.profilePic);
    END IF;

    -- Insert into Images table for coverPic
    IF NEW.coverPic IS NOT NULL AND NEW.coverPic != '' THEN
        INSERT INTO Images (userID, Img)
        VALUES (NEW.userID, NEW.coverPic);
    END IF;
END;
//

CREATE TRIGGER AddImageOnPostInsert
AFTER INSERT
ON Post FOR EACH ROW
BEGIN
    -- Insert into Images table for postImg
    IF NEW.postImg IS NOT NULL AND NEW.postImg != '' THEN
        INSERT INTO Images (userID, Img)
        VALUES (NEW.userID, NEW.postImg);
    END IF;
END;
//

DELIMITER ;

