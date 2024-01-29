CREATE TABLE Users (
    UserID VARCHAR(36) PRIMARY KEY,
    Name VARCHAR(200) NOT NULL,
    Email VARCHAR(200) NOT NULL UNIQUE,
    Username VARCHAR(200) NOT NULL UNIQUE,
    Country VARCHAR(200),
    Phone VARCHAR(15),
    Birthday DATE,
    Gender ENUM('Male', 'Female', 'Other'),
    PasswordHash VARCHAR(255) NOT NULL,
    VerificationState TINYINT(1) DEFAULT 0,
    SessionToken VARCHAR(255),
    CreatedTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (Email(100)),
    INDEX idx_username (Username(100))
);

CREATE TABLE Verification (
    VerificationID INT AUTO_INCREMENT PRIMARY KEY,
    UserID VARCHAR(36) NOT NULL,
    VerificationCodeHash VARCHAR(255) DEFAULT NULL,
    Salt VARCHAR(100) DEFAULT NULL,
    AttemptsCount TINYINT(1) DEFAULT 0,
    SentTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);


DELIMITER //
CREATE TRIGGER AddUserIDToVerification
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
    ContactID INT AUTO_INCREMENT PRIMARY KEY,
    UserID VARCHAR(36) NOT NULL,
    ContactType VARCHAR(50) NOT NULL,
    ContactValue VARCHAR(255) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

CREATE TABLE Profile (
    ProfileID INT AUTO_INCREMENT PRIMARY KEY,
    UserID VARCHAR(36) UNIQUE,
    ProfileImg VARCHAR(255),
    CoverImg VARCHAR(255),
    Home VARCHAR(100),
    Birthday DATE,
    RelationshipStatus VARCHAR(100),
    Mobile VARCHAR(15),
    ProfileStatus TINYINT(1) DEFAULT 0,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);


DELIMITER //

CREATE TRIGGER AddUserToContactInfoAndProfile
AFTER INSERT
ON users FOR EACH ROW
BEGIN
    -- Insert into ContactInfo table
    INSERT INTO ContactInfo (userID, contactType, contactValue)
    VALUES (NEW.userID, 'email', NEW.Email);

    -- Insert into Profile table
    INSERT INTO profile (userID, ProfileImg, CoverImg, Home, Birthday, RelationshipStatus, Mobile, ProfileStatus)
    VALUES (NEW.userID, NULL, NULL, NULL, NULL, NULL, NULL, 0);
END;

//
DELIMITER ;

CREATE TABLE Friends (
    FriendshipID INT AUTO_INCREMENT PRIMARY KEY,
    UserID VARCHAR(36),
    FriendID VARCHAR(36),
    requestStatus TINYINT DEFAULT 0,
    FOREIGN KEY (UserID) REFERENCES Profile(UserID),
    FOREIGN KEY (FriendID) REFERENCES Profile(UserID),
    UNIQUE KEY unique_friendship (UserID, FriendID)
);

CREATE TABLE Post (
    PostID VARCHAR(36) PRIMARY KEY,
    UserID VARCHAR(36),
    PostText VARCHAR(200),
    PostImg VARCHAR(200),
    PostTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

CREATE TABLE Story (
    StoryID INT AUTO_INCREMENT PRIMARY KEY,
    UserID VARCHAR(36),
    StoryImg VARCHAR(200),
    StoryText VARCHAR(200),
    StoryTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

CREATE TABLE Likes (
    LikeID INT AUTO_INCREMENT PRIMARY KEY,
    PostID VARCHAR(36),
    UserID VARCHAR(36),
    LikeStatus TINYINT DEFAULT 0,
    UNIQUE KEY unique_like (PostID, UserID),
    FOREIGN KEY (PostID) REFERENCES Post(PostID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

CREATE TABLE LikeCount (
    LikeCountID INT AUTO_INCREMENT PRIMARY KEY,
    PostID VARCHAR(36) UNIQUE,
    TotalLikeCount INT DEFAULT 0,
    FOREIGN KEY (PostID) REFERENCES Post(PostID)
);

CREATE TABLE Comments (
    CommentID INT AUTO_INCREMENT PRIMARY KEY,
    PostID VARCHAR(36),
    UserID VARCHAR(36),
    CommentText VARCHAR(255),
    CommentTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (PostID) REFERENCES Post(PostID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

CREATE TABLE Images(
    ImageID INT AUTO_INCREMENT PRIMARY KEY,
    UserID VARCHAR(36),
    Img VARCHAR(200),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

DELIMITER //

CREATE TRIGGER before_insert_Likes
BEFORE INSERT ON Likes
FOR EACH ROW
BEGIN
    DECLARE postCount INT;

    -- Check if PostID exists in LikeCount
    SELECT COUNT(*) INTO postCount FROM LikeCount WHERE PostID = NEW.PostID;

    IF postCount = 0 THEN
        -- PostID doesn't exist in LikeCount, insert a new row
        INSERT INTO LikeCount (PostID, TotalLikeCount) VALUES (NEW.PostID, 1);
    END IF;
END //

DELIMITER ;


-- Trigger for Profile table (ProfilePic)
DELIMITER //
CREATE TRIGGER addProfileImgToImgTable
AFTER INSERT
ON Profile FOR EACH ROW
BEGIN
    -- Insert into Images table for ProfilePic
    IF NEW.ProfileImg IS NOT NULL AND NEW.ProfileImg != '' THEN
        INSERT INTO images (UserID, Img)
        VALUES (NEW.UserID, NEW.ProfileImg);
    END IF;
END;
//
DELIMITER ;

-- Trigger for Profile table (CoverPic)
DELIMITER //
CREATE TRIGGER addCoverImgToImgTable
AFTER INSERT
ON Profile FOR EACH ROW
BEGIN
    -- Insert into Images table for CoverPic
    IF NEW.CoverImg IS NOT NULL AND NEW.CoverImg != '' THEN
        INSERT INTO Images (UserID, Img)
        VALUES (NEW.UserID, NEW.CoverImg);
    END IF;
END;
//
DELIMITER ;

-- Trigger for Post table (PostImg)
DELIMITER //
CREATE TRIGGER addPostImgToImgTable
AFTER INSERT
ON Post FOR EACH ROW
BEGIN
    -- Insert into Images table for PostImg
    IF NEW.PostImg IS NOT NULL AND NEW.PostImg != '' THEN
        INSERT INTO Images (UserID, Img)
        VALUES (NEW.UserID, NEW.PostImg);
    END IF;
END;
//
DELIMITER ;
