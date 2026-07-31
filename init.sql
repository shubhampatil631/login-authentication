CREATE DATABASE IF NOT EXISTS `app_db`;
USE `app_db`;

-- Table structure for table `users` (Authentication)
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `contacts` (CRUD operations)
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `address` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed initial contact
INSERT INTO `contacts` (`name`, `email`, `phone`, `address`) VALUES
('John Doe', 'john.doe@example.com', '+1-555-0199', '123 Main St, New York, NY'),
('Jane Smith', 'jane.smith@example.com', '+1-555-0142', '456 Oak Ave, Los Angeles, CA');
