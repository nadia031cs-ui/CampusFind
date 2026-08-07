-- CampusFind database schema
-- Batch 1: users / auth only. More tables (items, friends, messages) come in later batches.

CREATE DATABASE IF NOT EXISTS campusfind CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE campusfind;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    age INT NOT NULL,
    gender ENUM('Female','Male','Others') NOT NULL DEFAULT 'Others',
    phone VARCHAR(20) NOT NULL,
    dob DATE NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,       -- password_hash() output
    student_id VARCHAR(50) DEFAULT NULL,
    department VARCHAR(100) DEFAULT NULL,
    semester VARCHAR(50) DEFAULT NULL,
    batch VARCHAR(20) DEFAULT NULL,
    photo VARCHAR(255) DEFAULT 'default-profile.png',
    bio VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Batch 2: lost & found posts + notifications

CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(150) NOT NULL,
    item_date DATE NOT NULL,
    item_type ENUM('Electronics','Documents','Accessories','Books and Study Materials','Personal Items','Others') NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    likes_count INT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS item_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (item_id, user_id),   -- one like per user per post (original localStorage version had no such limit)
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipient_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    text VARCHAR(255) NOT NULL,
    link VARCHAR(255) DEFAULT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Batch 3: Friends, Messages, Profile/Settings, Change Password

CREATE TABLE IF NOT EXISTS friend_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    status ENUM('pending','declined') NOT NULL DEFAULT 'pending', -- accepted requests are deleted (see friends table) instead of tracked here
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_request (sender_id, receiver_id),
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS friends (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_low INT NOT NULL,   -- always the smaller of the two user ids
    user_high INT NOT NULL,  -- always the larger of the two user ids
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_pair (user_low, user_high),
    FOREIGN KEY (user_low) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (user_high) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    body TEXT DEFAULT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
