-- Database Schema for Bakery Production System
CREATE DATABASE bakery_production;

USE bakery_production;

-- Table for users (bakers and supervisor)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('baker', 'supervisor') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for recipes
CREATE TABLE recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    ingredients TEXT NOT NULL, -- JSON or serialized format for ingredient list
    steps TEXT NOT NULL, -- Preparation steps
    equipment_needed TEXT, -- List of equipment needed
    created_by INT, -- User who created the recipe (foreign key from users)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Table for production schedules
CREATE TABLE production_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(255) NOT NULL, -- Reference to the order
    production_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    total_quantity INT NOT NULL,
    status ENUM('scheduled', 'in-progress', 'completed', 'cancelled') DEFAULT 'scheduled',
    created_by INT, -- User who created the schedule (foreign key from users)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Table for batch tracking
CREATE TABLE batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_number VARCHAR(255) NOT NULL,
    production_schedule_id INT, -- Reference to production schedule
    start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    end_time TIMESTAMP,
    status ENUM('in-progress', 'completed', 'failed') DEFAULT 'in-progress',
    quality_check_status ENUM('pending', 'passed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (production_schedule_id) REFERENCES production_schedules(id)
);

-- Table for batch quality checks
CREATE TABLE batch_quality_checks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT, -- Reference to batch
    quality_check_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('passed', 'failed') NOT NULL,
    remarks TEXT,
    FOREIGN KEY (batch_id) REFERENCES batches(id)
);

