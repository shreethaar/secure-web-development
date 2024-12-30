-- Database: production_db

CREATE TABLE `recipes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `ingredients` TEXT NOT NULL,
    `steps` TEXT NOT NULL,
    `equipment` TEXT NOT NULL
);

CREATE TABLE `production_schedules` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_volume` INT NOT NULL,
    `staff_id` INT NOT NULL,
    `equipment_id` INT NOT NULL,
    `start_time` DATETIME NOT NULL,
    `end_time` DATETIME NOT NULL
);

CREATE TABLE `batches` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `batch_number` VARCHAR(255) NOT NULL,
    `start_time` DATETIME NOT NULL,
    `recipe_id` INT NOT NULL,
    `status` ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    `stage` VARCHAR(255) DEFAULT 'Initial'
);

CREATE TABLE `batch_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `batch_id` INT NOT NULL,
    `log_message` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL
);

