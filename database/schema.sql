-- ========================================
-- BARANGAY DOCUMENT REQUEST SYSTEM
-- DATABASE SCHEMA
-- ========================================

-- Create Database
CREATE DATABASE IF NOT EXISTS barangay_system;
USE barangay_system;

-- ========================================
-- TABLE 1: USERS (Residents)
-- ========================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    contact_number VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- TABLE 2: ADMINS (Barangay Staff)
-- ========================================
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- TABLE 3: DOCUMENT TYPES
-- ========================================
CREATE TABLE document_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    document_name VARCHAR(100) NOT NULL,
    requirements TEXT,
    processing_days INT DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- TABLE 4: DOCUMENT REQUESTS (Main Table)
-- ========================================
CREATE TABLE document_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    document_type_id INT NOT NULL,
    purpose TEXT NOT NULL,
    status ENUM('pending', 'processing', 'approved', 'rejected') DEFAULT 'pending',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    processed_date TIMESTAMP NULL,
    processed_by INT NULL,
    remarks TEXT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (document_type_id) REFERENCES document_types(id) ON DELETE RESTRICT,
    FOREIGN KEY (processed_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- INDEXES FOR BETTER PERFORMANCE
-- ========================================
CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_request_status ON document_requests(status);
CREATE INDEX idx_request_user ON document_requests(user_id);
CREATE INDEX idx_request_date ON document_requests(request_date);
