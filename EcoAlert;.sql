CREATE DATABASE IF NOT EXISTS EcoAlert;

USE EcoAlert;

CREATE TABLE IF NOT EXISTS Users (
    Email VARCHAR(100) PRIMARY KEY,
    Password VARCHAR(255) NOT NULL,
    Name VARCHAR(100) NOT NULL,
    UserLocation VARCHAR(100),
    ROLE ENUM(
        'communityMember',
        'organization'
    ) NOT NULL
);

CREATE TABLE IF NOT EXISTS CommunityMembers (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Email VARCHAR(100),
    FOREIGN KEY (Email) REFERENCES Users (Email) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Organizations (
    OrganizationID INT AUTO_INCREMENT PRIMARY KEY,
    Email VARCHAR(100),
    FOREIGN KEY (Email) REFERENCES Users (Email) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Reports (
    ReportID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    IssueType VARCHAR(100) NOT NULL,
    Status ENUM(
        'pending',
        'in_progress',
        'resolved'
    ) DEFAULT 'pending',
    IssueLocation VARCHAR(100) NOT NULL,
    DateReported TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UserUpvoteCount INT DEFAULT 0,
    FOREIGN KEY (UserID) REFERENCES CommunityMembers (UserID) ON DELETE SET NULL
);