-- Database Schema for Bakery Production System
CREATE DATABASE bakery_production;
USE bakery_production;

-- Table for users (bakers and supervisor)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('baker', 'supervisor') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_username (username)
);

-- Table for recipes
CREATE TABLE recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    ingredients JSON NOT NULL,
    steps JSON NOT NULL,
    equipment JSON,
    prep_time INT NOT NULL COMMENT 'Preparation time in minutes',
    yield INT NOT NULL COMMENT 'Number of items produced',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_name (name)
);

-- Table for production schedules
CREATE TABLE production_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT NOT NULL,
    order_id VARCHAR(255) NOT NULL,
    production_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    quantity INT NOT NULL,
    assigned_baker INT NOT NULL,
    equipment_needed JSON,
    status ENUM('scheduled', 'in_progress', 'completed', 'cancelled') DEFAULT 'scheduled',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id),
    FOREIGN KEY (assigned_baker) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_production_date (production_date),
    INDEX idx_assigned_baker (assigned_baker)
);

-- Table for batches
CREATE TABLE batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_number VARCHAR(255) NOT NULL,
    production_schedule_id INT NOT NULL,
    start_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    end_time TIMESTAMP NULL,
    status ENUM('in_progress', 'completed', 'failed') DEFAULT 'in_progress',
    notes TEXT,
    quality_checks JSON DEFAULT NULL COMMENT 'Array of quality check objects',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (production_schedule_id) REFERENCES production_schedules(id),
    INDEX idx_batch_number (batch_number),
    INDEX idx_status (status)
);

-- Table for batch quality checks (for detailed quality tracking)
CREATE TABLE batch_quality_checks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    checker_id INT NOT NULL,
    parameters JSON NOT NULL COMMENT 'Quality check parameters',
    result ENUM('passed', 'failed') NOT NULL,
    remarks TEXT,
    check_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (batch_id) REFERENCES batches(id),
    FOREIGN KEY (checker_id) REFERENCES users(id),
    INDEX idx_batch_id (batch_id),
    INDEX idx_check_time (check_time)
);
