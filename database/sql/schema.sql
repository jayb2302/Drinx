-- Drop the database if it exists
DROP DATABASE IF EXISTS drinx_db;

-- Create the database
CREATE DATABASE drinx_db;
USE drinx_db;

-- Disable foreign key checks to allow table drops
SET FOREIGN_KEY_CHECKS = 0;

-- Drop tables in reverse order to avoid foreign key conflicts
DROP TABLE IF EXISTS `cocktail_tags`;
DROP TABLE IF EXISTS `tags`;
DROP TABLE IF EXISTS `likes`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `follows`;
DROP TABLE IF EXISTS `cocktail_steps`;
DROP TABLE IF EXISTS `cocktail_ingredients`;
DROP TABLE IF EXISTS `cocktails`;
DROP TABLE IF EXISTS `ingredients`;
DROP TABLE IF EXISTS `ingredient_unit`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `difficulty_levels`;
DROP TABLE IF EXISTS `user_badges`;
DROP TABLE IF EXISTS `badges`;
DROP TABLE IF EXISTS `user_profile`;
DROP TABLE IF EXISTS `user_activity`;
DROP TABLE IF EXISTS `user_ranks`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `account_status`;

-- Re-enable foreign key checks after dropping tables
SET FOREIGN_KEY_CHECKS = 1;

-- Create the tables

CREATE TABLE `account_status` (
  `account_status_id` int PRIMARY KEY AUTO_INCREMENT,
  `status_name` varchar(50) UNIQUE NOT NULL
);

CREATE TABLE `user_ranks` (
  `rank_id` int PRIMARY KEY AUTO_INCREMENT,
  `rank_name` varchar(50) UNIQUE NOT NULL,
  `min_points` int NOT NULL UNIQUE
);

CREATE TABLE `badges` (
  `badge_id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) UNIQUE NOT NULL,
  `description` text
);

CREATE TABLE `categories` (
  `category_id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(100) UNIQUE NOT NULL
);

CREATE TABLE `difficulty_levels` (
  `difficulty_id` int PRIMARY KEY AUTO_INCREMENT,
  `difficulty_name` varchar(50) UNIQUE NOT NULL
);

CREATE TABLE `users` (
  `user_id` int PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(50) UNIQUE NOT NULL,
  `email` varchar(100) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,  -- Storing hashed passwords
  `account_status_id` int NOT NULL,
  `join_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL,
  `is_admin` BOOLEAN DEFAULT 0,
  FOREIGN KEY (`account_status_id`) REFERENCES `account_status`(`account_status_id`)
);

CREATE TABLE `cocktails` (
  `cocktail_id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `title` varchar(255) NOT NULL,
  `description` text,
  `prep_time` int,
  `image` varchar(255),
  `category_id` int,
  `difficulty_id` int,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`category_id`),
  FOREIGN KEY (`difficulty_id`) REFERENCES `difficulty_levels`(`difficulty_id`)
);

CREATE TABLE `user_profile` (
  `user_id` int PRIMARY KEY NOT NULL, 
  `first_name` varchar(50),
  `last_name` varchar(50),
  `profile_picture` varchar(255),
  `bio` text,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
);

CREATE TABLE `user_badges` (
  `user_id` int,
  `badge_id` int,
  `earned_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`, `badge_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`badge_id`) REFERENCES `badges`(`badge_id`) ON DELETE CASCADE
);

CREATE TABLE `user_activity` (
  `user_id` int PRIMARY KEY NOT NULL,  
  `rank_id` int,
  `points` int DEFAULT 0,
  `cocktails_uploaded` int DEFAULT 0,
  `likes_received` int DEFAULT 0,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`),  
  FOREIGN KEY (`rank_id`) REFERENCES `user_ranks`(`rank_id`)
);
CREATE TABLE `ingredients` (
  `ingredient_id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) UNIQUE NOT NULL
);
CREATE TABLE `ingredient_unit` (
  `unit_id` int PRIMARY KEY AUTO_INCREMENT,
  `unit_name` varchar(50) UNIQUE NOT NULL
);

CREATE TABLE `cocktail_ingredients` (
  `cocktail_id` int NOT NULL,
  `ingredient_id` int NOT NULL,
  `quantity` decimal(5,2) NOT NULL,
  `unit_id` int,
  PRIMARY KEY (`cocktail_id`, `ingredient_id`),
  FOREIGN KEY (`cocktail_id`) REFERENCES `cocktails`(`cocktail_id`) ON DELETE CASCADE,
  FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients`(`ingredient_id`),
  FOREIGN KEY (`unit_id`) REFERENCES `ingredient_unit`(`unit_id`)
);

CREATE TABLE `cocktail_steps` (
  `step_id` int PRIMARY KEY AUTO_INCREMENT,
  `cocktail_id` int,
  `step_number` int NOT NULL,
  `instruction` text NOT NULL,
  FOREIGN KEY (`cocktail_id`) REFERENCES `cocktails`(`cocktail_id`) ON DELETE CASCADE
);

CREATE TABLE `comments` (
  `comment_id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `cocktail_id` int,
  `parent_comment_id` int,
  `comment` text NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`cocktail_id`) REFERENCES `cocktails`(`cocktail_id`) ON DELETE CASCADE,
  FOREIGN KEY (`parent_comment_id`) REFERENCES `comments`(`comment_id`) ON DELETE CASCADE
);

CREATE TABLE `likes` (
  `like_id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `cocktail_id` int,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  UNIQUE (`user_id`, `cocktail_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`cocktail_id`) REFERENCES `cocktails`(`cocktail_id`) ON DELETE CASCADE
);

CREATE TABLE `tags` (
  `tag_id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) UNIQUE NOT NULL
);

CREATE TABLE `cocktail_tags` (
  `cocktail_id` int,
  `tag_id` int,
  PRIMARY KEY (`cocktail_id`, `tag_id`),
  FOREIGN KEY (`cocktail_id`) REFERENCES `cocktails`(`cocktail_id`) ON DELETE CASCADE,
  FOREIGN KEY (`tag_id`) REFERENCES `tags`(`tag_id`) ON DELETE CASCADE
);

CREATE TABLE `follows` (
  `user_id` int,
  `followed_user_id` int,
  `followed_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`, `followed_user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`followed_user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
);