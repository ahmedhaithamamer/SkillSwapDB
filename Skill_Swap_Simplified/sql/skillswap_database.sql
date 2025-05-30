-- SkillSwap Database Schema
-- This schema defines the database structure for the SkillSwap project
-- It includes all necessary tables with appropriate relationships and constraints

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS skillswap;
USE skillswap;

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` VARCHAR(36) PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `major` VARCHAR(100) NOT NULL,
  `year` INT NOT NULL,
  `bio` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `check_email_domain` CHECK (email LIKE '%@nu.edu.eg')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `user_interests`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_interests` (
  `interest_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` VARCHAR(36) NOT NULL,
  `interest` VARCHAR(100) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_user_interest` (`user_id`, `interest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `skills`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skills` (
  `skill_id` VARCHAR(36) PRIMARY KEY,
  `user_id` VARCHAR(36) NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `level` ENUM('beginner', 'intermediate', 'advanced', 'expert') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `learning_goals`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `learning_goals` (
  `goal_id` VARCHAR(36) PRIMARY KEY,
  `user_id` VARCHAR(36) NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `target_date` DATE NULL,
  `completed` BOOLEAN DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `connections`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `connections` (
  `connection_id` VARCHAR(36) PRIMARY KEY,
  `user_id` VARCHAR(36) NOT NULL,
  `connected_user_id` VARCHAR(36) NOT NULL,
  `status` ENUM('pending', 'accepted', 'rejected') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`connected_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `check_self_connection` CHECK (`user_id` <> `connected_user_id`),
  UNIQUE KEY `unique_connection` (`user_id`, `connected_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `messages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `messages` (
  `message_id` VARCHAR(36) PRIMARY KEY,
  `sender_id` VARCHAR(36) NOT NULL,
  `receiver_id` VARCHAR(36) NOT NULL,
  `content` TEXT NOT NULL,
  `is_read` BOOLEAN DEFAULT 0,
  `sent_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `check_self_message` CHECK (`sender_id` <> `receiver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `skill_requests`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skill_requests` (
  `request_id` VARCHAR(36) PRIMARY KEY,
  `requester_id` VARCHAR(36) NOT NULL,
  `provider_id` VARCHAR(36) NOT NULL,
  `skill_id` VARCHAR(36) NOT NULL,
  `message` TEXT NULL,
  `status` ENUM('pending', 'accepted', 'rejected', 'completed') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`requester_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`provider_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`skill_id`) REFERENCES `skills` (`skill_id`) ON DELETE CASCADE,
  CONSTRAINT `check_self_request` CHECK (`requester_id` <> `provider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `skill_categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skill_categories` (
  `category_id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `description` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `user_sessions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_sessions` (
  `session_id` VARCHAR(128) PRIMARY KEY,
  `user_id` VARCHAR(36) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` TEXT NULL,
  `last_activity` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `skill_ratings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skill_ratings` (
  `rating_id` VARCHAR(36) PRIMARY KEY,
  `skill_id` VARCHAR(36) NOT NULL,
  `rater_id` VARCHAR(36) NOT NULL,
  `rating` INT NOT NULL,
  `comment` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`skill_id`) REFERENCES `skills` (`skill_id`) ON DELETE CASCADE,
  FOREIGN KEY (`rater_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `check_rating_range` CHECK (rating BETWEEN 1 AND 5),
  UNIQUE KEY `unique_rating` (`skill_id`, `rater_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Initial data insertion for skill categories
-- -----------------------------------------------------
INSERT INTO `skill_categories` (`name`, `description`) VALUES 
('Technology', 'Computer science, programming, and tech-related skills'),
('Design', 'Graphic design, UX/UI, and visual arts'),
('Language', 'Foreign languages and linguistics'),
('Music', 'Musical instruments, singing, and music theory'),
('Academic', 'Subject-specific tutoring for university courses');

-- -----------------------------------------------------
-- Indexes for improved query performance
-- -----------------------------------------------------
CREATE INDEX `idx_users_email` ON `users` (`email`);
CREATE INDEX `idx_users_major` ON `users` (`major`);
CREATE INDEX `idx_skills_user_id` ON `skills` (`user_id`);
CREATE INDEX `idx_skills_category` ON `skills` (`category`);
CREATE INDEX `idx_skills_level` ON `skills` (`level`);
CREATE INDEX `idx_connections_user_id` ON `connections` (`user_id`);
CREATE INDEX `idx_connections_connected_user_id` ON `connections` (`connected_user_id`);
CREATE INDEX `idx_connections_status` ON `connections` (`status`);
CREATE INDEX `idx_messages_sender_id` ON `messages` (`sender_id`);
CREATE INDEX `idx_messages_receiver_id` ON `messages` (`receiver_id`);
CREATE INDEX `idx_messages_is_read` ON `messages` (`is_read`);
CREATE INDEX `idx_skill_requests_requester_id` ON `skill_requests` (`requester_id`);
CREATE INDEX `idx_skill_requests_provider_id` ON `skill_requests` (`provider_id`);
CREATE INDEX `idx_skill_requests_status` ON `skill_requests` (`status`); 