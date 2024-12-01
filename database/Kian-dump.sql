-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: database:3306
-- Generation Time: Nov 30, 2024 at 11:26 PM
-- Server version: 10.4.34-MariaDB-1:10.4.34+maria~ubu2004
-- PHP Version: 8.2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `drinx_db`
--
CREATE DATABASE IF NOT EXISTS `drinx_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `drinx_db`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `AddIngredientWithDefaultTag`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddIngredientWithDefaultTag` (IN `ingredientName` VARCHAR(255))   BEGIN
    DECLARE existingIngredientId INT;

    
    SELECT ingredient_id INTO existingIngredientId
    FROM ingredients
    WHERE name = ingredientName;

    
    IF existingIngredientId IS NULL THEN
        INSERT INTO ingredients (name) VALUES (ingredientName);
        SET existingIngredientId = LAST_INSERT_ID();
        
        
        INSERT INTO ingredient_tags (ingredient_id, tag_id)
        SELECT existingIngredientId, tag_id
        FROM tags
        WHERE name = 'Uncategorized';
    ELSE
        
        INSERT INTO ingredient_tags (ingredient_id, tag_id)
        SELECT existingIngredientId, tag_id
        FROM tags
        WHERE name = 'Uncategorized'
        AND NOT EXISTS (
            SELECT 1
            FROM ingredient_tags it
            WHERE it.ingredient_id = existingIngredientId
            AND it.tag_id = (SELECT tag_id FROM tags WHERE name = 'Uncategorized')
        );
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `account_status`
--

DROP TABLE IF EXISTS `account_status`;
CREATE TABLE `account_status` (
  `account_status_id` int(11) NOT NULL,
  `status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `account_status`
--

INSERT INTO `account_status` (`account_status_id`, `status_name`) VALUES
(1, 'Active'),
(3, 'Banned'),
(2, 'Suspended');

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

DROP TABLE IF EXISTS `badges`;
CREATE TABLE `badges` (
  `badge_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `badges`
--

INSERT INTO `badges` (`badge_id`, `name`, `description`) VALUES
(1, 'Juice Squeezer', 'Awarded for sharing your first recipe.'),
(2, 'Shaker Rookie', 'Awarded for sharing 5 recipes.'),
(3, 'Spirit Starter', 'Awarded for sharing 10 recipes.'),
(4, 'Glass Polisher', 'Awarded for sharing 15 recipes.'),
(5, 'Mixer Maverick', 'Awarded for sharing 20 recipes.'),
(6, 'Garnish Guru', 'Awarded for sharing 30 recipes.'),
(7, 'Spirit Sage', 'Awarded for sharing 40 recipes.'),
(8, 'Cocktail Connoisseur', 'Awarded for sharing 50 recipes.'),
(9, 'Mixology Maestro', 'Awarded for sharing 70 recipes.'),
(10, 'Distilled Virtuoso', 'Awarded for sharing 100 recipes.');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(1, 'Classic Cocktails'),
(6, 'Creamy Cocktails'),
(3, 'Fruity Cocktails'),
(8, 'Gin Cocktails'),
(5, 'Refreshing Cocktails'),
(4, 'Strong Cocktails'),
(9, 'Tequila Cocktails'),
(2, 'Tropical Cocktails'),
(10, 'Vodka Cocktails'),
(7, 'Whiskey Cocktails');

-- --------------------------------------------------------

--
-- Table structure for table `cocktails`
--

DROP TABLE IF EXISTS `cocktails`;
CREATE TABLE `cocktails` (
  `cocktail_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `prep_time` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `difficulty_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_sticky` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cocktails`
--

INSERT INTO `cocktails` (`cocktail_id`, `user_id`, `title`, `description`, `prep_time`, `image`, `category_id`, `difficulty_id`, `created_at`, `updated_at`, `is_sticky`) VALUES
(1, 1, 'Mojito Sun', 'A refreshing mint cocktail with lime and rum.', NULL, 'mojito.jpeg', 2, NULL, '2023-06-15 00:00:00', '2024-10-21 22:40:45', 0),
(2, 2, 'Old Fashioned', 'A whiskey-based cocktail with a hint of citrus.', NULL, 'old_fashioned.jpeg', 1, NULL, '2023-07-20 00:00:00', '2024-10-22 08:53:35', 0),
(3, 3, 'Margarita', 'A classic tequila cocktail with lime juice and salt.', NULL, 'margarita.jpeg', 2, 2, '2023-08-05 00:00:00', '2024-11-19 21:58:30', 0),
(4, 4, 'Negroni', 'A bittersweet cocktail made with gin, Campari, and sweet vermouth.', NULL, 'negroni.jpeg', 1, NULL, '2023-06-25 00:00:00', '0000-00-00 00:00:00', 0),
(5, 5, 'Pina Colada', 'A tropical cocktail made with rum, coconut cream, and pineapple juice.', NULL, 'pina_colada.jpeg', 2, NULL, '2023-07-30 00:00:00', '0000-00-00 00:00:00', 0),
(6, 6, 'Daiquiri', 'A rum cocktail with lime and sugar syrup.', NULL, 'classic-daiquiri.webp', 2, NULL, '2023-08-10 00:00:00', '2024-10-22 09:36:32', 0),
(7, 7, 'Cosmopolitan', 'A vodka cocktail with cranberry juice and triple sec.', NULL, 'cosmopolitan.jpeg', 2, NULL, '2023-06-18 00:00:00', '0000-00-00 00:00:00', 0),
(8, 8, 'Whiskey Sour', 'A classic cocktail made with whiskey and lemon juice.', NULL, 'whiskey_sour.jpeg', 1, NULL, '2023-08-22 00:00:00', '0000-00-00 00:00:00', 0),
(9, 9, 'Manhattan', 'A whiskey cocktail with sweet vermouth and bitters.', NULL, 'manhattan.jpeg', 1, NULL, '2023-09-12 00:00:00', '0000-00-00 00:00:00', 0),
(10, 10, 'Mai Tai', 'A tropical rum cocktail with orange and lime juices.', NULL, 'mai_tai.jpeg', 2, NULL, '2023-06-15 00:00:00', '0000-00-00 00:00:00', 0),
(11, 11, 'Gin and Tonic', 'A simple with gin and tonic water.', NULL, 'gin_and_tonic.jpeg', 1, NULL, '2023-08-20 00:00:00', '2024-10-22 08:02:55', 0),
(12, 12, 'Tequila Sunrise', 'A tequila cocktail with orange juice and grenadine.', NULL, 'tequila_sunrise.jpeg', 2, NULL, '2023-07-01 00:00:00', '0000-00-00 00:00:00', 1),
(13, 13, 'Bloody Mary', 'A vodka cocktail with tomato juice and spices.', NULL, 'bloody_mary.jpeg', 3, NULL, '2023-09-15 00:00:00', '0000-00-00 00:00:00', 0),
(14, 14, 'Screwdriver', 'A simple vodka cocktail with orange juice.', NULL, 'screwdriver.jpeg', 3, NULL, '2023-07-25 00:00:00', '0000-00-00 00:00:00', 0),
(15, 15, 'Cuba Libre', 'A rum cocktail with cola and lime juice.', NULL, 'cuba_libre.jpeg', 3, NULL, '2023-08-05 00:00:00', '0000-00-00 00:00:00', 0),
(16, 1, 'Long Island Iced Tea', 'A strong cocktail with vodka, rum, tequila, gin, and triple sec.', NULL, 'long_island_iced_tea.jpeg', 2, NULL, '2023-09-08 00:00:00', '0000-00-00 00:00:00', 0),
(17, 2, 'Tom Collins', 'A gin cocktail with lemon juice and soda water.', NULL, 'tom_collins.jpeg', 1, NULL, '2023-07-28 00:00:00', '0000-00-00 00:00:00', 0),
(18, 3, 'French 75', 'A gin cocktail with lemon juice and champagne.', NULL, 'french_75.jpeg', 1, NULL, '2023-08-15 00:00:00', '0000-00-00 00:00:00', 0),
(19, 4, 'Caipirinha', 'A Brazilian cocktail made with cachaça, lime, and sugar.', NULL, 'caipirinha.jpeg', 3, NULL, '2023-09-05 00:00:00', '0000-00-00 00:00:00', 0),
(20, 5, 'Martini', 'A classic gin cocktail with vermouth.', NULL, 'martini.jpeg', 1, NULL, '2023-06-22 00:00:00', '0000-00-00 00:00:00', 0),
(21, 6, 'Paloma', 'A tequila cocktail with grapefruit soda and lime.', NULL, 'paloma.jpeg', 2, 2, '2023-09-18 00:00:00', '2024-11-19 21:55:38', 0),
(22, 7, 'Aperol Spritz', 'A refreshing cocktail with Aperol, Prosecco, and soda water.', NULL, 'aperol_spritz.jpeg', 2, NULL, '2023-08-20 00:00:00', '0000-00-00 00:00:00', 0),
(23, 8, 'Sazerac', 'A whiskey cocktail with bitters and sugar.', NULL, 'sazerac.jpeg', 1, NULL, '2023-07-12 00:00:00', '0000-00-00 00:00:00', 0),
(24, 9, 'Moscow Mule', 'A vodka cocktail with ginger beer and lime.', NULL, 'moscow_mule.jpeg', 2, NULL, '2023-06-30 00:00:00', '0000-00-00 00:00:00', 0),
(25, 10, 'Sidecar', 'A brandy cocktail with triple sec and lemon juice.', NULL, 'sidecar.jpeg', 1, NULL, '2023-08-03 00:00:00', '0000-00-00 00:00:00', 0),
(26, 11, 'Mint Julep', 'A bourbon cocktail with mint and sugar syrup.', NULL, 'mint-j.jpeg', 5, 1, '2023-09-22 00:00:00', '2024-11-08 20:44:35', 0),
(27, 12, 'Pisco Sour', 'A South American cocktail with pisco, lime juice, and egg white.', NULL, '', 2, NULL, '2023-07-19 00:00:00', '0000-00-00 00:00:00', 0),
(28, 13, 'Brandy Alexander', 'A creamy cocktail with brandy and cream.', NULL, '', 3, NULL, '2023-09-25 00:00:00', '0000-00-00 00:00:00', 0),
(29, 14, 'Rum Punch', 'A rum cocktail with pineapple juice and grenadine.', NULL, '', 2, NULL, '2023-08-02 00:00:00', '0000-00-00 00:00:00', 0),
(30, 15, 'Gin Fizz', 'A gin cocktail with lemon juice and soda water.', NULL, '', 1, NULL, '2023-06-17 00:00:00', '0000-00-00 00:00:00', 0),
(31, 26, 'Classic Daiquiri', 'One of the most classic sour cocktails! This daiquiri recipe has the perfect balance of boozy, tart and sweet with rum, lime and sweetener.', NULL, 'classic-daiquiri.webp', 5, NULL, '2024-10-17 12:53:32', '2024-10-17 19:11:19', 0),
(32, 26, 'Amaretto Sour', 'An amaretto sour a classic sour cocktail made with amaretto liqueur.', NULL, NULL, 5, 3, '2024-10-17 13:32:38', '2024-11-13 20:09:43', 0),
(33, 26, 'Frozen Strawberry Daiquiri', 'Strawberry Daiquiris are everything that comes to mind when I think of cocktails:', NULL, 'Strawberry-Daiquiri.webp', 3, 1, '2024-10-17 16:49:00', '2024-10-21 16:59:22', 0),
(34, 26, 'Frozen Strawberry Daiquiri', 'Strawberry Daiquiris are everything that comes to mind when I think of cocktails:', NULL, 'Strawberry-Daiquiri.webp', 5, 2, '2024-10-17 16:52:03', '2024-11-12 13:47:57', 0),
(35, 21, 'Rhubarb Gin', 'Use seasonal rhubarb to make this G&T-with-a-difference, or top the finished gin with soda water for a refreshing and gloriously pink summertime drink', NULL, 'rhubarb-gin.webp', 8, 1, '2024-10-22 11:05:32', '0000-00-00 00:00:00', 0),
(37, 21, 'Woo Woo', 'Mix vodka, peach schnapps, cranberry juice and fresh lime to make this perfect party cocktail, garnished with a lime wedge (and maybe a tiny umbrella too)', NULL, 'woo-woo.webp', 5, 2, '2024-10-22 11:20:05', '0000-00-00 00:00:00', 0),
(39, 39, 'Banana Daiquiri', 'Bring sunshine to your cocktail repertoire in the form of a punchy, fun banana daiquiri. Garnish with banana slices, lime wedges or cocktail cherries', NULL, 'Banana-daiquiri.webp', 4, 2, '2024-10-22 17:57:12', '2024-11-08 19:28:52', 0),
(40, 22, 'Mojito', 'Some descritptiion', NULL, 'mojito.webp', 8, 2, '2024-10-28 15:45:49', '0000-00-00 00:00:00', 0),
(43, 26, 'Cocktail', 'Cocktail description', NULL, '9dc4afa5f50a5440.jpeg', 8, 1, '2024-11-12 12:43:45', '2024-11-13 19:59:09', 0),
(44, 26, 'Jónína', 'Description', NULL, 'ce54a68bdb1cf846.jpg', 5, 2, '2024-11-12 13:17:17', '2024-11-13 19:55:36', 0),
(45, 26, 'test', 'test', NULL, '2b3f6fc72721bb80.webp', 5, 1, '2024-11-13 20:39:50', '2024-11-14 15:37:17', 0),
(46, 21, 'test', 'test', NULL, 'd7624a930b45c265.webp', 3, 1, '2024-11-14 14:21:39', '2024-11-14 14:23:00', 0),
(47, 21, 'Test345', 'test', NULL, 'baf596d0f834784f.jpeg', 1, 2, '2024-11-15 21:11:23', '0000-00-00 00:00:00', NULL),
(48, 21, 'test', 'test', NULL, 'f3bf745f7b3f5ef2.webp', 3, 2, '2024-11-17 23:49:02', '0000-00-00 00:00:00', NULL),
(49, 27, 'RUDOLPH’S TIPSY PUNCH', 'This Christmas cocktail recipe is great for party entertaining and it can easily be made kid-friendly.', NULL, 'a9f25d4c3429e5d6.webp', 3, 1, '2024-11-19 10:01:59', '0000-00-00 00:00:00', 0),
(50, 21, 'Test345', 'hehe', NULL, 'c882839f1455c786.webp', 10, 1, '2024-11-19 21:44:19', '2024-11-19 21:52:39', 0),
(51, 1, 'Cocktail 2', NULL, NULL, NULL, NULL, NULL, '2024-11-28 19:44:31', '0000-00-00 00:00:00', 0),
(52, 1, 'Cocktail 3', NULL, NULL, NULL, NULL, NULL, '2024-11-28 19:44:31', '0000-00-00 00:00:00', 0),
(53, 1, 'Cocktail 4', NULL, NULL, NULL, NULL, NULL, '2024-11-28 19:44:31', '0000-00-00 00:00:00', 0),
(54, 1, 'Cocktail 5', NULL, NULL, NULL, NULL, NULL, '2024-11-28 19:44:31', '0000-00-00 00:00:00', 0),
(55, 1, 'Cocktail 6', NULL, NULL, NULL, NULL, NULL, '2024-11-28 19:44:31', '0000-00-00 00:00:00', 0),
(56, 1, 'Cocktail 2', NULL, NULL, NULL, NULL, NULL, '2024-11-28 19:48:28', '0000-00-00 00:00:00', 0),
(57, 1, 'Cocktail 3', NULL, NULL, NULL, NULL, NULL, '2024-11-28 19:48:28', '0000-00-00 00:00:00', 0),
(58, 1, 'Cocktail 4', NULL, NULL, NULL, NULL, NULL, '2024-11-28 19:48:28', '0000-00-00 00:00:00', 0),
(59, 1, 'Cocktail 5', NULL, NULL, NULL, NULL, NULL, '2024-11-28 19:48:28', '0000-00-00 00:00:00', 0),
(60, 1, 'Cocktail 6', NULL, NULL, NULL, NULL, NULL, '2024-11-28 19:48:28', '0000-00-00 00:00:00', 0),
(61, 1, 'Cocktail 2', NULL, NULL, NULL, NULL, NULL, '2024-11-28 20:00:18', '0000-00-00 00:00:00', 0),
(62, 1, 'Cocktail 3', NULL, NULL, NULL, NULL, NULL, '2024-11-28 20:00:18', '0000-00-00 00:00:00', 0),
(63, 1, 'Cocktail 4', NULL, NULL, NULL, NULL, NULL, '2024-11-28 20:00:18', '0000-00-00 00:00:00', 0),
(64, 1, 'Cocktail 5', NULL, NULL, NULL, NULL, NULL, '2024-11-28 20:00:18', '0000-00-00 00:00:00', 0),
(65, 1, 'Cocktail 6', NULL, NULL, NULL, NULL, NULL, '2024-11-28 20:00:18', '0000-00-00 00:00:00', 0),
(66, 1, 'Cocktail 2', NULL, NULL, NULL, NULL, NULL, '2024-11-29 00:22:53', '0000-00-00 00:00:00', 0),
(67, 1, 'Cocktail 3', NULL, NULL, NULL, NULL, NULL, '2024-11-29 00:22:53', '0000-00-00 00:00:00', 0),
(68, 1, 'Cocktail 4', NULL, NULL, NULL, NULL, NULL, '2024-11-29 00:22:53', '0000-00-00 00:00:00', 0),
(69, 1, 'Cocktail 5', NULL, NULL, NULL, NULL, NULL, '2024-11-29 00:22:53', '0000-00-00 00:00:00', 0),
(70, 1, 'Cocktail 6', NULL, NULL, NULL, NULL, NULL, '2024-11-29 00:22:53', '0000-00-00 00:00:00', 0);

--
-- Triggers `cocktails`
--
DROP TRIGGER IF EXISTS `update_user_badges`;
DELIMITER $$
CREATE TRIGGER `update_user_badges` AFTER INSERT ON `cocktails` FOR EACH ROW BEGIN
    -- Determine the new badge_id based on the user's recipe count
    SET @new_badge_id = (
        CASE
            WHEN (SELECT COUNT(*) FROM cocktails WHERE user_id = NEW.user_id) >= 100 THEN 10
            WHEN (SELECT COUNT(*) FROM cocktails WHERE user_id = NEW.user_id) >= 70 THEN 9
            WHEN (SELECT COUNT(*) FROM cocktails WHERE user_id = NEW.user_id) >= 50 THEN 8
            WHEN (SELECT COUNT(*) FROM cocktails WHERE user_id = NEW.user_id) >= 40 THEN 7
            WHEN (SELECT COUNT(*) FROM cocktails WHERE user_id = NEW.user_id) >= 30 THEN 6
            WHEN (SELECT COUNT(*) FROM cocktails WHERE user_id = NEW.user_id) >= 20 THEN 5
            WHEN (SELECT COUNT(*) FROM cocktails WHERE user_id = NEW.user_id) >= 15 THEN 4
            WHEN (SELECT COUNT(*) FROM cocktails WHERE user_id = NEW.user_id) >= 10 THEN 3
            WHEN (SELECT COUNT(*) FROM cocktails WHERE user_id = NEW.user_id) >= 5 THEN 2
            ELSE 1
        END
    );

    -- Check if the badge already exists and is marked as current
    IF NOT EXISTS (
        SELECT 1
        FROM user_badges
        WHERE user_id = NEW.user_id AND badge_id = @new_badge_id AND is_current = 1
    ) THEN
        -- Mark all previous badges for the user as not current
        UPDATE user_badges
        SET is_current = 0
        WHERE user_id = NEW.user_id;

        -- Insert the new badge as the current badge
        INSERT INTO user_badges (user_id, badge_id, earned_at, is_current)
        VALUES (NEW.user_id, @new_badge_id, NOW(), 1);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cocktail_ingredients`
--

DROP TABLE IF EXISTS `cocktail_ingredients`;
CREATE TABLE `cocktail_ingredients` (
  `cocktail_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` decimal(5,2) NOT NULL,
  `unit_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cocktail_ingredients`
--

INSERT INTO `cocktail_ingredients` (`cocktail_id`, `ingredient_id`, `quantity`, `unit_id`) VALUES
(1, 1, 50.00, 2),
(1, 2, 100.00, 1),
(1, 14, 100.00, 1),
(1, 48, 25.00, 1),
(1, 64, 50.00, 2),
(1, 67, 50.00, 2),
(1, 68, 50.00, 2),
(1, 80, 50.00, 2),
(2, 5, 60.00, 1),
(2, 18, 2.00, 3),
(2, 45, 1.00, 7),
(2, 64, 30.00, 1),
(3, 3, 20.00, 2),
(3, 6, 20.00, 2),
(4, 4, 30.00, 1),
(4, 16, 30.00, 1),
(4, 17, 30.00, 1),
(5, 2, 45.00, 1),
(5, 11, 60.00, 2),
(5, 12, 60.00, 2),
(6, 2, 50.00, 1),
(6, 6, 20.00, 2),
(6, 9, 10.00, 3),
(7, 1, 40.00, 1),
(7, 14, 30.00, 1),
(7, 15, 20.00, 1),
(8, 5, 50.00, 1),
(8, 7, 20.00, 2),
(8, 9, 10.00, 3),
(9, 5, 50.00, 1),
(9, 16, 30.00, 1),
(9, 18, 2.00, 3),
(10, 2, 50.00, 1),
(10, 6, 20.00, 2),
(10, 13, 20.00, 2),
(11, 4, 40.00, 1),
(11, 10, 100.00, 7),
(12, 3, 50.00, 1),
(12, 13, 60.00, 2),
(12, 19, 10.00, 3),
(13, 1, 40.00, 1),
(13, 13, 100.00, 7),
(13, 19, 10.00, 3),
(14, 1, 40.00, 1),
(14, 14, 100.00, 7),
(15, 2, 50.00, 1),
(15, 6, 20.00, 2),
(15, 10, 100.00, 7),
(16, 1, 30.00, 1),
(16, 2, 30.00, 1),
(16, 3, 30.00, 1),
(16, 4, 30.00, 1),
(16, 7, 20.00, 2),
(16, 10, 100.00, 7),
(16, 15, 30.00, 1),
(17, 4, 40.00, 1),
(17, 7, 20.00, 2),
(17, 9, 10.00, 3),
(17, 10, 100.00, 7),
(18, 4, 30.00, 1),
(18, 7, 20.00, 2),
(18, 18, 2.00, 3),
(19, 3, 50.00, 1),
(19, 6, 20.00, 2),
(19, 9, 10.00, 3),
(20, 4, 60.00, 1),
(20, 16, 30.00, 1),
(21, 3, 20.00, 2),
(21, 6, 20.00, 2),
(21, 20, 20.00, 2),
(21, 21, 20.00, 2),
(22, 6, 20.00, 2),
(22, 15, 20.00, 1),
(22, 22, 30.00, 1),
(22, 23, 60.00, 2),
(23, 5, 50.00, 1),
(23, 9, 10.00, 3),
(23, 18, 2.00, 3),
(24, 1, 40.00, 1),
(24, 6, 20.00, 2),
(24, 24, 100.00, 7),
(24, 25, 20.00, 2),
(25, 7, 20.00, 2),
(25, 15, 20.00, 1),
(25, 25, 40.00, 1),
(26, 5, 50.00, 1),
(26, 8, 5.00, 3),
(26, 9, 10.00, 3),
(27, 6, 20.00, 2),
(27, 9, 10.00, 3),
(27, 26, 50.00, 1),
(27, 27, 20.00, 2),
(28, 9, 10.00, 3),
(28, 25, 40.00, 1),
(28, 28, 60.00, 2),
(29, 2, 50.00, 1),
(29, 12, 60.00, 2),
(29, 19, 10.00, 3),
(30, 4, 40.00, 1),
(30, 7, 20.00, 2),
(30, 10, 100.00, 7),
(34, 41, 20.00, 3),
(34, 57, 20.00, 3),
(37, 1, 50.00, 1),
(37, 48, 25.00, 1),
(39, 6, 10.00, 1),
(39, 41, 50.00, 1),
(39, 52, 25.00, 1),
(40, 8, 5.00, 2),
(40, 41, 30.00, 3),
(43, 55, 2.00, 3),
(43, 56, 2.00, 3),
(44, 41, 20.00, 5),
(44, 57, 20.00, 5),
(44, 58, 20.00, 6),
(45, 53, 4.00, 3),
(45, 59, 3.00, 3),
(46, 1, 25.00, 3),
(46, 59, 4.00, 3),
(47, 64, 23.00, 3),
(48, 124, 1.00, 4),
(49, 1, 2.00, 6),
(49, 13, 2.00, 6),
(49, 14, 3.00, 6),
(49, 59, 1.00, 7),
(49, 128, 2.00, 6),
(49, 129, 1.00, 6),
(50, 1, 3.00, 3),
(50, 129, 3.00, 3),
(50, 130, 3.00, 3);

--
-- Triggers `cocktail_ingredients`
--
DROP TRIGGER IF EXISTS `auto_tag_cocktail`;
DELIMITER $$
CREATE TRIGGER `auto_tag_cocktail` AFTER INSERT ON `cocktail_ingredients` FOR EACH ROW BEGIN
    -- Log the trigger action
    INSERT INTO trigger_debug (cocktail_id, tag_id, ingredient_id)
    SELECT NEW.cocktail_id, it.tag_id, NEW.ingredient_id
    FROM ingredient_tags it
    WHERE it.ingredient_id = NEW.ingredient_id;
    
    -- Actual logic
    IF NOT EXISTS (
        SELECT 1
        FROM cocktail_tags
        WHERE cocktail_id = NEW.cocktail_id
          AND tag_id IN (
              SELECT tag_id
              FROM ingredient_tags
              WHERE ingredient_id = NEW.ingredient_id
          )
    ) THEN
        INSERT INTO cocktail_tags (cocktail_id, tag_id)
        SELECT NEW.cocktail_id, it.tag_id
        FROM ingredient_tags it
        WHERE it.ingredient_id = NEW.ingredient_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cocktail_steps`
--

DROP TABLE IF EXISTS `cocktail_steps`;
CREATE TABLE `cocktail_steps` (
  `step_id` int(11) NOT NULL,
  `cocktail_id` int(11) DEFAULT NULL,
  `step_number` int(11) NOT NULL,
  `instruction` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cocktail_steps`
--

INSERT INTO `cocktail_steps` (`step_id`, `cocktail_id`, `step_number`, `instruction`) VALUES
(10, 4, 1, 'Stir gin, sweet vermouth, and Campari with ice.'),
(11, 4, 2, 'Strain into a glass filled with ice.'),
(12, 4, 3, 'Garnish with an orange peel.'),
(13, 5, 1, 'Blend rum, coconut cream, and pineapple juice with ice until smooth.'),
(14, 5, 2, 'Pour into a chilled glass and garnish with a pineapple slice and cherry.'),
(17, 7, 1, 'Shake vodka, cranberry juice, lime juice, and triple sec with ice.'),
(18, 7, 2, 'Strain into a chilled glass and garnish with a lime twist.'),
(19, 8, 1, 'Shake whiskey, lemon juice, and sugar syrup with ice.'),
(20, 8, 2, 'Strain into a glass filled with ice.'),
(21, 8, 3, 'Garnish with a cherry and orange slice.'),
(22, 9, 1, 'Stir whiskey, sweet vermouth, and bitters with ice.'),
(23, 9, 2, 'Strain into a chilled glass and garnish with a cherry.'),
(24, 10, 1, 'Shake rum, lime juice, and orange liqueur with ice.'),
(25, 10, 2, 'Strain into a glass filled with ice.'),
(26, 10, 3, 'Garnish with a mint sprig and lime wedge.'),
(30, 12, 1, 'Pour tequila and orange juice into a glass filled with ice.'),
(31, 12, 2, 'Slowly pour grenadine over the back of a spoon.'),
(32, 12, 3, 'Garnish with a slice of orange and a cherry.'),
(33, 13, 1, 'Shake vodka, tomato juice, and spices with ice.'),
(34, 13, 2, 'Strain into a glass filled with ice.'),
(35, 13, 3, 'Garnish with a celery stick and lemon wedge.'),
(36, 14, 1, 'Pour vodka and orange juice into a glass filled with ice.'),
(37, 14, 2, 'Stir gently and garnish with an orange slice.'),
(38, 15, 1, 'Pour rum, cola, and lime juice into a glass filled with ice.'),
(39, 15, 2, 'Stir gently and garnish with a lime wedge.'),
(40, 16, 1, 'Shake vodka, rum, tequila, gin, and triple sec with ice.'),
(41, 16, 2, 'Strain into a glass filled with ice and top with cola.'),
(42, 17, 1, 'Shake gin, lemon juice, and sugar syrup with ice.'),
(43, 17, 2, 'Strain into a glass filled with ice and top with soda water.'),
(44, 18, 1, 'Shake gin, lemon juice, and sugar syrup with ice.'),
(45, 18, 2, 'Strain into a glass and top with champagne.'),
(46, 19, 1, 'Muddle lime wedges and sugar in a glass.'),
(47, 19, 2, 'Fill the glass with ice and add cachaça.'),
(48, 19, 3, 'Stir gently and garnish with a lime wedge.'),
(49, 20, 1, 'Stir gin and vermouth with ice.'),
(50, 20, 2, 'Strain into a chilled glass and garnish with an olive or lemon twist.'),
(226, 11, 1, 'Pour gin into a glass filled with ice.'),
(227, 11, 2, 'Top with tonic water and stir gently.'),
(228, 11, 3, 'Garnish with a lime wedge.'),
(229, 2, 1, 'Place a sugar cube in a glass and add a few dashes of bitters.'),
(230, 2, 2, 'Add whiskey and a large ice cube.'),
(231, 2, 3, 'Stir gently and garnish with an orange peel.'),
(232, 6, 1, 'Shake rum, lime juice, and sugar syrup with ice.'),
(233, 6, 2, 'Strain into a chilled glass and garnish with a lime wheel.'),
(236, 1, 1, 'Fill a cocktail shaker with ice then add the vodka, peach schnapps, cranberry juice and a few drops of lime juice'),
(237, 1, 2, 'Shake really well then strain into a tumbler with extra ice. Garnish with a wedge of lime.'),
(238, 37, 1, 'Fill a cocktail shaker with ice then add the vodka, peach schnapps, cranberry juice and a few drops of lime juice. '),
(239, 37, 2, 'Shake really well then strain into a tumbler with extra ice. Garnish with a wedge of lime.'),
(247, 40, 1, 'Test Test '),
(251, 39, 1, 'Put the rum, banana liqueur, lime juice, banana and ice in a blender suitable for crushing ice, and blitz until smooth.'),
(252, 39, 2, 'Pour the drink into a hurricane or other tall glass, and garnish with banana slices or chips, lime wedges or cocktail cherries, if you like (we skewered two cocktail cherries and a lime wedge on a cocktail stick)'),
(254, 26, 1, 'In a Julep cup or rocks glass, lightly muddle the mint leaves in the simple syrup.'),
(258, 34, 1, 'Test this step add'),
(283, 44, 1, 'Fyrsta step'),
(284, 44, 2, 'second step.'),
(285, 44, 3, 'Third step.'),
(286, 43, 1, 'Step'),
(287, 32, 1, 'Add amaretto, bourbon, lemon juice, simple syrup and egg white to a shaker and dry-shake (no ice) for 15 seconds.'),
(290, 46, 1, 'test'),
(291, 45, 1, 'test'),
(292, 47, 1, 'test'),
(293, 48, 1, 'test'),
(294, 49, 1, 'Combine all the ingredients in a large pitcher or punch bowl. Stir well.'),
(295, 49, 2, 'Serve over ice and garnish with cranberries or maraschino cherries and/or a rosemary sprig.'),
(298, 50, 1, '44'),
(299, 21, 1, '2'),
(300, 3, 1, 'Rim the glass with salt and set aside.'),
(301, 3, 2, 'Shake tequila, lime juice, and triple sec with ice.'),
(302, 3, 3, 'Strain into the prepared glass and garnish with a lime wedge.');

-- --------------------------------------------------------

--
-- Table structure for table `cocktail_tags`
--

DROP TABLE IF EXISTS `cocktail_tags`;
CREATE TABLE `cocktail_tags` (
  `cocktail_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cocktail_tags`
--

INSERT INTO `cocktail_tags` (`cocktail_id`, `tag_id`, `created_at`) VALUES
(1, 4, '2024-11-15 19:39:30'),
(1, 44, '2024-11-15 20:20:14'),
(2, 4, '2024-11-15 19:40:00'),
(2, 44, '2024-11-15 19:40:00'),
(3, 1, '2024-11-19 21:58:30'),
(3, 52, '2024-11-19 21:58:30'),
(21, 1, '2024-11-19 21:55:38'),
(21, 20, '2024-11-19 21:55:38'),
(21, 25, '2024-11-19 21:55:38'),
(21, 52, '2024-11-19 21:55:38'),
(47, 4, '2024-11-15 21:11:23'),
(47, 43, '2024-11-15 21:11:23'),
(49, 1, '2024-11-19 10:01:59'),
(49, 22, '2024-11-19 10:01:59'),
(49, 42, '2024-11-19 10:01:59'),
(49, 48, '2024-11-19 10:01:59'),
(50, 48, '2024-11-19 21:52:39');

-- --------------------------------------------------------

--
-- Stand-in structure for view `cocktail_tag_categories`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `cocktail_tag_categories`;
CREATE TABLE `cocktail_tag_categories` (
`tag_id` int(11)
,`tag_name` varchar(100)
,`tag_category` varchar(100)
,`tag_label` varchar(100)
);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cocktail_id` int(11) DEFAULT NULL,
  `parent_comment_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `user_id`, `cocktail_id`, `parent_comment_id`, `comment`, `created_at`) VALUES
(1, 1, 1, NULL, 'Refreshing drink! Perfect for summer.', '2023-06-25 00:00:00'),
(2, 2, 1, NULL, 'Love the mint flavor, a perfect balance.', '2023-06-26 00:00:00'),
(3, 3, 2, NULL, 'Old but gold, can never go wrong with an Old Fashioned.', '2023-07-20 00:00:00'),
(4, 4, 3, NULL, 'Margarita is always a party starter!', '2023-08-15 00:00:00'),
(5, 5, 4, NULL, 'Negroni is my favorite! Bittersweet goodness.', '2023-06-30 00:00:00'),
(6, 6, 5, NULL, 'Pina Colada for life! Love the tropical vibe.', '2023-07-05 00:00:00'),
(7, 7, 6, NULL, 'Simple but delicious Daiquiri, refreshing.', '2023-08-10 00:00:00'),
(8, 8, 7, NULL, 'Cosmopolitan is so classy and light, love it.', '2023-06-18 00:00:00'),
(9, 9, 8, NULL, 'Whiskey sour perfection!', '2023-08-25 00:00:00'),
(10, 10, 9, NULL, 'Manhattan is a classic, strong but smooth.', '2023-09-12 00:00:00'),
(11, 26, 2, 3, 'True that', '2024-10-22 20:15:35'),
(12, 26, 2, 3, 'True that', '2024-10-22 20:19:17'),
(13, 26, 7, 8, 'I agree', '2024-10-22 21:58:26'),
(14, 26, 7, 8, 'I like this cocktail', '2024-10-22 21:58:39'),
(15, 26, 2, 3, 'Þetta er comment', '2024-10-22 22:08:19'),
(16, 26, 2, 3, 'This is test ', '2024-10-22 22:28:26'),
(17, 26, 2, NULL, 'OLD IS GOLD', '2024-10-22 22:30:06'),
(18, 26, 39, NULL, 'Comment', '2024-11-06 10:01:49'),
(22, 21, 39, NULL, 'Commenting', '2024-11-10 13:25:29'),
(29, 21, 16, NULL, 'Comment', '2024-11-10 13:58:02'),
(30, 21, 39, 22, 'hello', '2024-11-10 14:27:07'),
(31, 21, 31, NULL, 'hello', '2024-11-10 14:51:18'),
(32, 21, 31, 31, 'hello\r\n', '2024-11-10 14:51:32'),
(33, 26, 44, NULL, 'Here is a comment', '2024-11-13 10:54:35'),
(35, 26, 44, NULL, 'It&#039;s a good recipe', '2024-11-13 10:57:35'),
(36, 26, 44, 35, 'Yes I agree', '2024-11-13 16:56:38'),
(39, 26, 44, NULL, 'I&#039;m just trying', '2024-11-13 17:06:21'),
(40, 26, 34, NULL, '', '2024-11-13 17:23:34'),
(41, 26, 34, NULL, '', '2024-11-13 17:51:43'),
(43, 21, 49, NULL, 'hello', '2024-11-20 12:00:15');

-- --------------------------------------------------------

--
-- Table structure for table `difficulty_levels`
--

DROP TABLE IF EXISTS `difficulty_levels`;
CREATE TABLE `difficulty_levels` (
  `difficulty_id` int(11) NOT NULL,
  `difficulty_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `difficulty_levels`
--

INSERT INTO `difficulty_levels` (`difficulty_id`, `difficulty_name`) VALUES
(1, 'Easy'),
(3, 'Hard'),
(2, 'Medium');

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

DROP TABLE IF EXISTS `follows`;
CREATE TABLE `follows` (
  `user_id` int(11) NOT NULL,
  `followed_user_id` int(11) NOT NULL,
  `followed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`user_id`, `followed_user_id`, `followed_at`) VALUES
(1, 2, '2023-06-15 00:00:00'),
(1, 3, '2023-06-16 00:00:00'),
(2, 3, '2023-06-17 00:00:00'),
(3, 4, '2023-06-18 00:00:00'),
(4, 5, '2023-06-19 00:00:00'),
(5, 6, '2023-06-20 00:00:00'),
(6, 7, '2023-06-21 00:00:00'),
(7, 8, '2023-06-22 00:00:00'),
(8, 9, '2023-06-23 00:00:00'),
(9, 10, '2023-06-24 00:00:00'),
(21, 21, '2024-11-09 21:26:26'),
(21, 26, '2024-11-09 20:01:30'),
(22, 26, '2024-11-09 20:34:18'),
(24, 2, '2024-11-09 20:39:25'),
(24, 25, '2024-11-09 20:38:07'),
(24, 26, '2024-11-09 20:38:00'),
(26, 6, '2024-11-01 11:41:24'),
(26, 26, '2024-11-01 11:39:18'),
(26, 33, '2024-11-06 12:16:53');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`ingredient_id`, `name`) VALUES
(131, '1'),
(132, '2'),
(133, '3'),
(156, '3ff'),
(134, '4'),
(135, '5'),
(141, '65'),
(136, 'aa'),
(146, 'aaa'),
(22, 'Aperol'),
(157, 'aq'),
(52, 'Banana Liqueur '),
(64, 'Basil'),
(18, 'Bitters'),
(55, 'Blackberry'),
(61, 'Blueberry'),
(25, 'Brandy'),
(17, 'Campari'),
(47, 'Caster Sugar'),
(53, 'Champagne'),
(129, 'Cherry Juice'),
(68, 'Chili'),
(11, 'Coconut Cream'),
(80, 'Coconut Water'),
(79, 'Coffee Liqueur'),
(58, 'Coke'),
(14, 'Cranberry Juice'),
(28, 'Cream'),
(56, 'Cucumber'),
(150, 'cwc'),
(153, 'daf'),
(160, 'dcc'),
(163, 'dd'),
(161, 'ddad'),
(138, 'ddd'),
(147, 'df'),
(67, 'Dragonfruit'),
(159, 'dws'),
(152, 'eee'),
(27, 'Egg White'),
(73, 'Elderflower Cordial'),
(151, 'eqf'),
(66, 'Fanta'),
(143, 'fvv'),
(139, 'gg'),
(142, 'ghf'),
(4, 'Gin'),
(128, 'Ginger Ale'),
(24, 'Ginger Beer'),
(20, 'Grapefruit Soda'),
(124, 'Ground nutmeg'),
(51, 'Ice'),
(10, 'Ice Cubes'),
(144, 'jhg'),
(130, 'Kocaloca'),
(59, 'Lemon'),
(7, 'Lemon Juice'),
(6, 'Lime Juice'),
(8, 'Mint Leaves'),
(45, 'Orange'),
(13, 'Orange Juice'),
(48, 'Peach Schnaps'),
(63, 'Pear'),
(57, 'Pepsi'),
(65, 'Pineapple'),
(12, 'Pineapple Juice'),
(46, 'Pink Rhubarb Stalks'),
(26, 'Pisco'),
(21, 'Prosecco'),
(149, 'qa'),
(140, 'qq'),
(69, 'Rose Water'),
(2, 'Rum'),
(164, 'sds'),
(162, 'sfsf'),
(23, 'Soda Water'),
(19, 'Spices'),
(9, 'Sugar Syrup'),
(148, 'swdw'),
(16, 'Sweet Vermouth'),
(154, 'sws'),
(3, 'Tequila'),
(15, 'Triple Sec'),
(158, 'ttr'),
(70, 'Vanilla Extract'),
(1, 'Vodka'),
(50, 'Watermelon'),
(49, 'Watermelon Liqueur'),
(137, 'wcw'),
(155, 'wer'),
(5, 'Whiskey'),
(41, 'White Rum'),
(165, 'wscd'),
(145, 'zxcvf');

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_tags`
--

DROP TABLE IF EXISTS `ingredient_tags`;
CREATE TABLE `ingredient_tags` (
  `ingredient_tag_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ingredient_tags`
--

INSERT INTO `ingredient_tags` (`ingredient_tag_id`, `ingredient_id`, `tag_id`, `created_at`) VALUES
(6, 64, 4, '2024-11-15 19:11:37'),
(42, 64, 43, '2024-11-15 20:08:30'),
(47, 69, 7, '2024-11-15 20:29:21'),
(56, 79, 44, '2024-11-15 20:52:37'),
(58, 18, 8, '2024-11-15 21:08:54'),
(59, 61, 47, '2024-11-15 21:09:00'),
(60, 25, 54, '2024-11-15 21:09:10'),
(61, 22, 8, '2024-11-15 21:19:16'),
(62, 52, 54, '2024-11-15 21:19:46'),
(63, 17, 8, '2024-11-15 21:19:52'),
(64, 47, 7, '2024-11-15 21:19:59'),
(65, 53, 25, '2024-11-15 21:20:10'),
(66, 11, 2, '2024-11-15 21:20:26'),
(67, 28, 2, '2024-11-15 21:20:35'),
(72, 67, 47, '2024-11-15 21:23:08'),
(73, 58, 22, '2024-11-15 21:23:21'),
(74, 21, 25, '2024-11-15 21:23:48'),
(75, 49, 54, '2024-11-15 21:24:02'),
(76, 51, 36, '2024-11-15 21:25:17'),
(77, 10, 36, '2024-11-15 21:25:23'),
(78, 14, 22, '2024-11-15 21:26:02'),
(79, 27, 37, '2024-11-15 21:27:13'),
(80, 66, 22, '2024-11-15 21:27:21'),
(81, 4, 51, '2024-11-15 21:27:27'),
(82, 6, 1, '2024-11-15 21:28:10'),
(83, 7, 9, '2024-11-15 21:28:17'),
(84, 8, 37, '2024-11-15 21:28:24'),
(85, 45, 42, '2024-11-15 21:28:35'),
(86, 20, 20, '2024-11-15 21:30:20'),
(87, 59, 42, '2024-11-15 21:30:35'),
(88, 13, 1, '2024-11-15 21:31:43'),
(89, 48, 54, '2024-11-15 21:32:33'),
(90, 1, 48, '2024-11-15 21:44:05'),
(91, 5, 49, '2024-11-15 21:44:20'),
(92, 41, 50, '2024-11-15 21:44:27'),
(93, 57, 22, '2024-11-15 21:45:41'),
(94, 50, 47, '2024-11-17 12:57:21'),
(95, 56, 22, '2024-11-17 13:15:17'),
(96, 63, 47, '2024-11-17 13:15:31'),
(97, 2, 50, '2024-11-17 13:38:04'),
(98, 9, 7, '2024-11-17 13:38:12'),
(99, 3, 52, '2024-11-17 13:38:19'),
(103, 68, 5, '2024-11-17 13:41:13'),
(105, 80, 40, '2024-11-17 13:45:12'),
(106, 65, 40, '2024-11-17 13:45:19'),
(107, 12, 7, '2024-11-17 13:45:27'),
(118, 23, 16, '2024-11-17 17:55:06'),
(151, 64, 2, '2024-11-17 23:22:00'),
(152, 73, 24, '2024-11-17 23:22:17'),
(153, 70, 16, '2024-11-17 23:23:45'),
(154, 15, 6, '2024-11-17 23:24:10'),
(155, 24, 5, '2024-11-17 23:26:50'),
(156, 19, 5, '2024-11-17 23:28:07'),
(157, 46, 9, '2024-11-17 23:28:55'),
(158, 16, 7, '2024-11-17 23:29:57'),
(160, 55, 47, '2024-11-17 23:31:17'),
(174, 26, 11, '2024-11-18 11:37:59');

--
-- Triggers `ingredient_tags`
--
DROP TRIGGER IF EXISTS `remove_uncategorized_tag`;
DELIMITER $$
CREATE TRIGGER `remove_uncategorized_tag` AFTER INSERT ON `ingredient_tags` FOR EACH ROW BEGIN
    
    IF EXISTS (
        SELECT 1
        FROM ingredient_tags
        WHERE ingredient_id = NEW.ingredient_id
          AND tag_id = (SELECT tag_id FROM tags WHERE name = 'Uncategorized')
    ) THEN
        
        DELETE FROM ingredient_tags
        WHERE ingredient_id = NEW.ingredient_id
          AND tag_id = (SELECT tag_id FROM tags WHERE name = 'Uncategorized');
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_unit`
--

DROP TABLE IF EXISTS `ingredient_unit`;
CREATE TABLE `ingredient_unit` (
  `unit_id` int(11) NOT NULL,
  `unit_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ingredient_unit`
--

INSERT INTO `ingredient_unit` (`unit_id`, `unit_name`) VALUES
(3, 'cl'),
(6, 'cup'),
(5, 'dash'),
(1, 'ml'),
(2, 'oz'),
(7, 'piece'),
(4, 'tsp');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cocktail_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `user_id`, `cocktail_id`, `created_at`) VALUES
(1, 1, 1, '2023-06-26 00:00:00'),
(2, 2, 1, '2023-06-27 00:00:00'),
(3, 3, 2, '2023-07-21 00:00:00'),
(4, 4, 3, '2023-08-16 00:00:00'),
(5, 5, 4, '2023-07-01 00:00:00'),
(6, 6, 5, '2023-07-06 00:00:00'),
(7, 7, 6, '2023-08-11 00:00:00'),
(8, 8, 7, '2023-06-19 00:00:00'),
(9, 9, 8, '2023-08-26 00:00:00'),
(10, 10, 9, '2023-09-13 00:00:00'),
(16, 26, 5, '2024-10-28 12:19:19'),
(17, 26, 6, '2024-10-28 12:19:50'),
(50, 26, 2, '2024-10-28 14:25:06'),
(54, 26, 15, '2024-10-28 14:36:46'),
(55, 26, 13, '2024-10-28 14:36:48'),
(58, 26, 11, '2024-10-28 14:37:30'),
(59, 26, 14, '2024-10-28 14:37:33'),
(61, 26, 35, '2024-10-28 14:37:45'),
(69, 22, 7, '2024-10-28 16:56:51'),
(70, 22, 12, '2024-10-28 16:57:02'),
(75, 22, 6, '2024-10-28 21:00:19'),
(76, 22, 5, '2024-10-28 21:00:20'),
(77, 22, 8, '2024-10-28 21:03:44'),
(78, 22, 11, '2024-10-28 21:06:17'),
(81, 22, 35, '2024-10-28 21:09:56'),
(84, 22, 37, '2024-10-28 21:24:15'),
(88, 22, 39, '2024-10-28 21:28:11'),
(90, 22, 31, '2024-10-28 21:29:38'),
(109, 22, 3, '2024-10-29 09:56:23'),
(112, 22, 10, '2024-10-29 10:00:56'),
(113, 22, 1, '2024-10-29 10:03:13'),
(114, 22, 40, '2024-10-29 10:03:20'),
(115, 26, 40, '2024-10-29 10:04:48'),
(117, 26, 1, '2024-10-29 10:23:04'),
(119, 26, 3, '2024-10-29 10:26:44'),
(120, 26, 10, '2024-10-29 10:28:14'),
(121, 26, 18, '2024-10-29 10:29:50'),
(123, 26, 16, '2024-10-29 10:34:41'),
(124, 26, 22, '2024-10-29 10:35:13'),
(125, 26, 23, '2024-10-29 10:39:04'),
(126, 26, 24, '2024-10-29 10:40:23'),
(127, 26, 21, '2024-10-29 10:42:25'),
(128, 26, 34, '2024-10-29 10:45:08'),
(129, 26, 37, '2024-10-29 10:46:52'),
(130, 26, 4, '2024-10-29 10:48:19'),
(131, 26, 7, '2024-10-29 10:48:45'),
(134, 26, 20, '2024-10-29 10:52:07'),
(135, 26, 19, '2024-10-29 10:53:21'),
(136, 26, 17, '2024-10-29 10:59:14'),
(138, 26, 32, '2024-11-01 20:21:06'),
(140, 26, 9, '2024-11-09 15:20:17'),
(142, 26, 30, '2024-11-09 15:21:23'),
(143, 26, 39, '2024-11-09 15:22:03'),
(146, 41, 33, '2024-11-09 18:15:20'),
(147, 41, 32, '2024-11-09 18:15:23'),
(148, 24, 39, '2024-11-09 20:37:39'),
(149, 24, 16, '2024-11-09 20:37:42'),
(150, 24, 26, '2024-11-09 20:37:46'),
(151, 21, 2, '2024-11-09 23:00:14'),
(153, 21, 35, '2024-11-10 11:02:44'),
(154, 21, 37, '2024-11-10 11:02:47'),
(158, 26, 44, '2024-11-13 19:55:16'),
(161, 21, 39, '2024-11-18 13:47:46'),
(165, 21, 27, '2024-11-18 13:59:01'),
(168, 21, 47, '2024-11-18 14:06:36'),
(171, 21, 49, '2024-11-19 19:48:01');

--
-- Triggers `likes`
--
DROP TRIGGER IF EXISTS `after_like_delete`;
DELIMITER $$
CREATE TRIGGER `after_like_delete` AFTER DELETE ON `likes` FOR EACH ROW BEGIN
    UPDATE user_activity ua
    JOIN cocktails c ON ua.user_id = c.user_id
    SET ua.likes_received = ua.likes_received - 1
    WHERE c.cocktail_id = OLD.cocktail_id;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `after_like_insert`;
DELIMITER $$
CREATE TRIGGER `after_like_insert` AFTER INSERT ON `likes` FOR EACH ROW BEGIN
    UPDATE user_activity ua
    JOIN cocktails c ON ua.user_id = c.user_id
    SET ua.likes_received = ua.likes_received + 1
    WHERE c.cocktail_id = NEW.cocktail_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `tag_category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tag_id`, `name`, `tag_category_id`) VALUES
(1, 'Citrus', 1),
(2, 'Creamy', 4),
(4, 'Herbal', 1),
(5, 'Spicy', 1),
(6, 'Strong', 1),
(7, 'Sweet', 1),
(8, 'Bitter', 1),
(9, 'Tangy', 3),
(10, 'Savory', 1),
(11, 'Umami', 1),
(12, 'Smoky', 4),
(13, 'Floral', 3),
(14, 'Nutty', 1),
(15, 'Earthy', 1),
(16, 'Vanilla', 1),
(17, 'Peppery', 3),
(18, 'Caramel', 1),
(19, 'Relaxing', 2),
(20, 'Energizing', 2),
(21, 'Indulgent', 5),
(22, 'Refreshing', 2),
(23, 'Sophisticated', 2),
(24, 'Adventurous', 2),
(25, 'Celebration', 3),
(26, 'Wedding', 3),
(27, 'Casual', 3),
(28, 'Nightcap', 3),
(29, 'GameDay', 3),
(30, 'Festive', 3),
(31, 'DateNight', 3),
(32, 'MovieNight', 3),
(33, 'Spring', 3),
(34, 'Fall', 3),
(35, 'WinterWarmer', 3),
(36, 'Iced', 5),
(37, 'RoomTemperature', 5),
(38, 'Warm', 5),
(39, 'Frozen', 5),
(40, 'SummerFruits', 4),
(41, 'AutumnSpices', 4),
(42, 'WinterCitrus', 4),
(43, 'SpringHerbs', 4),
(44, 'Uncategorized', 6),
(47, 'Fruity', 1),
(48, 'Vodka', 7),
(49, 'Whiskey', 7),
(50, 'Rum', 7),
(51, 'Gin', 7),
(52, 'Tequila', 7),
(54, 'Liqueurs', 7);

-- --------------------------------------------------------

--
-- Table structure for table `tag_categories`
--

DROP TABLE IF EXISTS `tag_categories`;
CREATE TABLE `tag_categories` (
  `tag_category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tag_categories`
--

INSERT INTO `tag_categories` (`tag_category_id`, `category_name`) VALUES
(1, 'Flavor'),
(7, 'Liquor'),
(2, 'Mood'),
(3, 'Occasion'),
(5, 'Season'),
(4, 'Temperature'),
(6, 'Uncategorized');

-- --------------------------------------------------------

--
-- Table structure for table `trigger_debug`
--

DROP TABLE IF EXISTS `trigger_debug`;
CREATE TABLE `trigger_debug` (
  `cocktail_id` int(11) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  `ingredient_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `trigger_debug`
--

INSERT INTO `trigger_debug` (`cocktail_id`, `tag_id`, `ingredient_id`, `created_at`) VALUES
(1, 44, 80, '2024-11-15 21:04:12'),
(47, 4, 64, '2024-11-15 21:11:23'),
(47, 43, 64, '2024-11-15 21:11:23'),
(49, 1, 13, '2024-11-19 10:01:59'),
(49, 22, 14, '2024-11-19 10:01:59'),
(49, 48, 1, '2024-11-19 10:01:59'),
(49, 42, 59, '2024-11-19 10:01:59'),
(50, 48, 1, '2024-11-19 21:52:39'),
(50, 48, 1, '2024-11-19 21:52:39'),
(21, 52, 3, '2024-11-19 21:55:38'),
(21, 1, 6, '2024-11-19 21:55:38'),
(21, 20, 20, '2024-11-19 21:55:38'),
(21, 25, 21, '2024-11-19 21:55:38'),
(21, 52, 3, '2024-11-19 21:55:38'),
(21, 1, 6, '2024-11-19 21:55:38'),
(21, 20, 20, '2024-11-19 21:55:38'),
(21, 25, 21, '2024-11-19 21:55:38'),
(3, 52, 3, '2024-11-19 21:58:30'),
(3, 1, 6, '2024-11-19 21:58:30'),
(3, 52, 3, '2024-11-19 21:58:30'),
(3, 1, 6, '2024-11-19 21:58:30');

-- --------------------------------------------------------

--
-- Table structure for table `trigger_logs`
--

DROP TABLE IF EXISTS `trigger_logs`;
CREATE TABLE `trigger_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `recipe_count` int(11) DEFAULT NULL,
  `badge_id` int(11) DEFAULT NULL,
  `triggered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `trigger_logs`
--

INSERT INTO `trigger_logs` (`id`, `user_id`, `recipe_count`, `badge_id`, `triggered_at`) VALUES
(1, 1, 8, 2, '2024-11-28 19:48:28'),
(2, 1, 9, 2, '2024-11-28 19:48:28'),
(3, 1, 10, 3, '2024-11-28 19:48:28'),
(4, 1, 11, 3, '2024-11-28 19:48:28'),
(5, 1, 12, 3, '2024-11-28 19:48:28'),
(6, 1, 13, 3, '2024-11-28 20:00:18'),
(7, 1, 14, 3, '2024-11-28 20:00:18'),
(8, 1, 15, 4, '2024-11-28 20:00:18'),
(9, 1, 16, 4, '2024-11-28 20:00:18'),
(10, 1, 17, 4, '2024-11-28 20:00:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_status_id` int(11) NOT NULL,
  `join_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `account_status_id`, `join_date`, `last_login`, `is_admin`) VALUES
(1, 'john_doe', 'john.doe@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-01-10 00:00:00', NULL, 0),
(2, 'jane_smith', 'jane.smith@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-02-15 00:00:00', NULL, 0),
(3, 'emily_clark', 'emily.clark@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-03-12 00:00:00', NULL, 0),
(4, 'william_jones', 'william.jones@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-04-22 00:00:00', NULL, 0),
(5, 'mary_johnson', 'mary.johnson@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 2, '2023-05-30 00:00:00', NULL, 0),
(6, 'alex_brown', 'alex.brown@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-06-05 00:00:00', NULL, 0),
(7, 'sarah_white', 'sarah.white@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-07-10 00:00:00', NULL, 0),
(8, 'michael_smith', 'michael.smith@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-08-15 00:00:00', NULL, 0),
(9, 'linda_garcia', 'linda.garcia@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-09-20 00:00:00', NULL, 0),
(10, 'david_miller', 'david.miller@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-10-25 00:00:00', NULL, 0),
(11, 'laura_davis', 'laura.davis@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-11-02 00:00:00', NULL, 0),
(12, 'james_wilson', 'james.wilson@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-11-10 00:00:00', NULL, 0),
(13, 'karen_taylor', 'karen.taylor@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-12-15 00:00:00', NULL, 0),
(14, 'daniel_moore', 'daniel.moore@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-01-05 00:00:00', NULL, 0),
(15, 'patricia_anderson', 'patricia.anderson@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-02-12 00:00:00', NULL, 0),
(16, 'robert_thomas', 'robert.thomas@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-03-20 00:00:00', NULL, 0),
(17, 'barbara_jackson', 'barbara.jackson@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 2, '2023-04-15 00:00:00', NULL, 0),
(18, 'charles_martin', 'charles.martin@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-05-18 00:00:00', NULL, 0),
(19, 'susan_lee', 'susan.lee@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 1, '2023-06-25 00:00:00', NULL, 0),
(20, 'paul_walker', 'paul.walker@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', 3, '2023-07-30 00:00:00', NULL, 0),
(21, 'admin_user', 'admin@example.com', '$2y$10$kiJxtnUuP4XZtcO6WOWLa.J1l4Aq5HAgPkPqxu38r54DOVlZV6KGq', 1, '2023-12-01 00:00:00', '2024-11-19 19:43:49', 1),
(22, 'joninaab', 'joninaa@live.com', '$2y$10$NnfzvCmJVuqY5RYhDirT4uR4Ndi6aQzVaC.6Gyjw5KDasZqXRzexy', 1, '2024-10-15 13:50:32', '2024-11-09 20:12:47', 0),
(24, 'irena', 'irena@hello.is', '$2y$10$8zrxhicjgoANGWzi1CuJ.OlkLn0eE8z3wZ8WlTRHCxc6Ok2SHu22i', 1, '2024-10-15 13:59:35', '2024-11-09 20:37:37', 0),
(25, 'Jonina', 'jonina@hello.is', '$2y$10$ZWHBI1MUR7TVH6nXBMDv2umpjQ/2kwjdEjkKGEVmZQbiE5rw56DVO', 1, '2024-10-16 10:27:42', NULL, 0),
(26, 'Kian', 'kian@hello.is', '$2y$10$1KEclIW2ecNk8o27IrOKPOC2gr3Z4lTWxJYwvUm6tv3nwgiANTA/y', 1, '2024-10-16 10:30:51', '2024-11-12 13:25:02', 0),
(27, 'Jon', 'jon@hello.is', '$2y$10$RSKGVqTvNUzeKAzvQXPilO3hu/8vWZsHbRd2VHs5tHoI6TIgr82.m', 1, '2024-10-16 10:34:56', '2024-11-19 09:38:54', 0),
(32, 'JonFreyr', 'jonfreyr@hello.is', '$2y$10$7TCawSbsceQ/PcuCD2OO6upTXOe6YitAS6CUH7z/5dUxTWHqvoJji', 1, '2024-10-16 11:28:30', NULL, 0),
(33, 'JonF', 'jonf@hello.is', '$2y$10$4WvrqMMABbb64UPU9Rx8teRCBjnxr0sXpkrKR7yg7CVigZfYJpTmC', 1, '2024-10-16 11:31:11', NULL, 0),
(35, 'Kian23', 'kian23@hello.id', '$2y$10$BwwM1vydTBCFzTo5Lea5.u4s1fwW96f.7J9JaP6o6rqi6tG1bPMyu', 3, '2024-10-16 13:06:32', NULL, 0),
(37, 'JoninaBjarna', 'joninabjarna@hello.is', '$2y$10$jikprUMFsS5L9kyIP4j0Au13eVDaPo8DmAGkmsRRHpO4tZgfvS1g2', 1, '2024-10-16 19:06:44', '2024-10-16 19:06:55', 0),
(39, 'JonFreyrS', 'jonfs@hello.is', '$2y$10$JIQXPCuQGAat51HoEtN9bOYJBm5iMDGJim09RirD84ZKEUUA3k3lu', 1, '2024-10-22 17:41:16', '2024-10-22 17:41:28', 0),
(40, 'Val', 'val@hello.is', '$2y$10$eYaljXNQOVHPqnP.njEbRO570nMpS5Vekmhz8ynZ9MWfrD4etW/HC', 1, '2024-11-01 10:49:05', '2024-11-01 10:49:45', 0),
(41, 'Sillybird', 'silly@silly.com', '$2y$10$U4jsztH1qcsf6B/2OWcDoe5Vazunz3wjWmWXDUVzoqUYnkQ2les1G', 1, '2024-11-09 17:47:18', '2024-11-09 17:47:36', 0),
(42, 'adminBunny', 'admin@bunny.com', '$2y$10$P3RIY4b3ORGJmkz.K0OFseQdfqZw0FS.jWp16PcyZVMI7T0GzabFm', 1, '2024-11-29 00:33:32', '2024-11-30 20:58:24', 0),
(43, 'badgeBunny', 'badge@bunny.com', '$2y$10$.0ZYbrXqVYy5tnuSNNqObe0yJ.Syvn7F/E18r934Wq6bV1UWC2H1a', 1, '2024-11-30 14:53:59', '2024-11-30 15:41:17', 0),
(44, '2badgeBunny', '2badge@bunny.com', '$2y$10$9gzpVM1hx6OyUojFczoZBOaZkzfW4XD02CAXafoOeGEralY0UBCF2', 1, '2024-11-30 15:52:52', '2024-11-30 20:59:20', 0),
(45, '3badgebunny', '3badge@bunny.com', '$2y$10$7VVOAn8cyQ31GZa3cH8VeOutYZ.d5zUy8bp7Vv.01PfluXrzj0WjW', 1, '2024-11-30 16:09:38', '2024-11-30 20:58:05', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

DROP TABLE IF EXISTS `user_activity`;
CREATE TABLE `user_activity` (
  `user_id` int(11) NOT NULL,
  `rank_id` int(11) DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `cocktails_uploaded` int(11) DEFAULT 0,
  `likes_received` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`user_id`, `rank_id`, `points`, `cocktails_uploaded`, `likes_received`) VALUES
(1, NULL, 0, 0, 6),
(2, NULL, 0, 0, 4),
(3, NULL, 0, 0, 4),
(4, NULL, 0, 0, 3),
(5, NULL, 0, 0, 4),
(6, NULL, 0, 0, 4),
(7, NULL, 0, 0, 4),
(8, NULL, 0, 0, 3),
(9, NULL, 0, 0, 3),
(10, NULL, 0, 0, 2),
(11, NULL, 0, 0, 3),
(12, NULL, 0, 0, 2),
(13, NULL, 0, 0, 1),
(14, NULL, 0, 0, 1),
(15, NULL, 0, 0, 2),
(16, NULL, 0, 0, 0),
(17, NULL, 0, 0, 0),
(18, NULL, 0, 0, 0),
(19, NULL, 0, 0, 0),
(20, NULL, 0, 0, 0),
(21, NULL, 0, 0, 7),
(22, NULL, 0, 0, 2),
(24, NULL, 0, 0, 0),
(25, NULL, 0, 0, 0),
(26, NULL, 0, 0, 6),
(27, NULL, 0, 0, 1),
(32, NULL, 0, 0, 0),
(33, NULL, 0, 0, 0),
(35, NULL, 0, 0, 0),
(37, NULL, 0, 0, 0),
(39, NULL, 0, 0, 4),
(40, NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_badges`
--

DROP TABLE IF EXISTS `user_badges`;
CREATE TABLE `user_badges` (
  `user_id` int(11) NOT NULL,
  `badge_id` int(11) NOT NULL,
  `earned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_current` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_badges`
--

INSERT INTO `user_badges` (`user_id`, `badge_id`, `earned_at`, `is_current`) VALUES
(1, 1, '2024-11-28 19:29:18', 0),
(1, 3, '2024-11-28 19:53:16', 0),
(1, 4, '2024-11-28 20:00:18', 0),
(1, 5, '2024-11-29 00:22:53', 1),
(2, 1, '2024-11-28 19:29:18', 0),
(3, 1, '2024-11-28 19:29:18', 0),
(4, 1, '2024-11-28 19:29:18', 0),
(5, 1, '2024-11-28 19:29:18', 0),
(6, 1, '2024-11-28 19:29:18', 0),
(7, 1, '2024-11-28 19:29:18', 0),
(8, 1, '2024-11-28 19:29:18', 0),
(9, 1, '2024-11-28 19:29:18', 0),
(10, 1, '2024-11-28 19:29:18', 0),
(11, 1, '2024-11-28 19:29:18', 0),
(12, 1, '2024-11-28 19:29:18', 0),
(13, 1, '2024-11-28 19:29:18', 0),
(14, 1, '2024-11-28 19:29:18', 0),
(15, 1, '2024-11-28 19:29:18', 0),
(21, 2, '2024-11-28 19:29:18', 0),
(22, 1, '2024-11-28 19:29:18', 0),
(26, 2, '2024-11-28 19:29:18', 0),
(27, 1, '2024-11-28 19:29:18', 0),
(39, 1, '2024-11-28 19:29:18', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

DROP TABLE IF EXISTS `user_profile`;
CREATE TABLE `user_profile` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`user_id`, `first_name`, `last_name`, `profile_picture`, `bio`) VALUES
(1, 'John', 'Doe', 'user-default.svg', 'Loves classic cocktails and experimenting with flavors. A whiskey lover at heart.'),
(2, 'Jane', 'Smith', 'user-default.svg', 'A mixologist in training with a passion for tequila.'),
(3, 'Emily', 'Clark', 'user-default.svg', 'I enjoy crafting cocktails with fresh ingredients and unique twists.'),
(4, 'William', 'Jones', 'user-default.svg', 'Whiskey enthusiast and cocktail purist. Fan of Old Fashioned.'),
(5, 'Mary', 'Johnson', 'user-default.svg', 'Amateur bartender, always experimenting with new recipes.'),
(6, 'Alex', 'Brown', 'user-default.svg', 'Cocktail lover and part-time bartender.'),
(7, 'Sarah', 'White', 'user-default.svg', 'Passionate about all things tropical and fruity.'),
(8, 'Michael', 'Smith', 'user-default.svg', 'Crafting signature cocktails and exploring new recipes.'),
(9, 'Linda', 'Garcia', 'user-default.svg', 'Home bartender sharing fun and easy cocktail recipes.'),
(10, 'David', 'Miller', 'user-default.svg', 'Experimenting with strong, bitter cocktails.'),
(11, 'Laura', 'Davis', 'user-default.svg', 'Loves making easy cocktails for home parties.'),
(12, 'James', 'Wilson', 'user-default.svg', 'I create cocktails with a focus on balance and flavor.'),
(13, 'Karen', 'Taylor', 'user-default.svg', 'Tequila and margarita enthusiast.'),
(14, 'Daniel', 'Moore', 'user-default.svg', 'Exploring craft cocktails and unique mixers.'),
(15, 'Patricia', 'Anderson', 'user-default.svg', 'Creating cocktails inspired by global flavors.'),
(16, 'Robert', 'Thomas', 'user-default.svg', 'Gin and tonic master, experimenting with different botanicals.'),
(17, 'Barbara', 'Jackson', 'user-default.svg', 'Creating fun, party-friendly cocktails.'),
(18, 'Charles', 'Martin', 'user-default.svg', 'Making cocktails that are simple and delicious.'),
(19, 'Susan', 'Lee', 'user-default.svg', 'Enthusiastic about fruity, tropical cocktails.'),
(20, 'Paul', 'Walker', 'user-default.svg', 'Always searching for the perfect rum cocktail.'),
(21, 'Admin', 'Adminson', 'dd024a3c97a5f95b.jpg', ''),
(22, 'JÃ³nÃ­na', 'BjarnadÃ³ttir', 'e8cfd859466a1308.jpg', ''),
(24, 'Írena', 'Jónsdóttir', '0fa4df797933122c.jpg', ''),
(25, NULL, NULL, NULL, NULL),
(26, 'Kian', 'Miridoozini', '3ad6c2faa500efdf.jpg', ''),
(27, 'John', 'Snow', '249cbb77b0a499e6.png', 'My passion is mixing'),
(32, NULL, NULL, NULL, NULL),
(33, NULL, NULL, NULL, NULL),
(35, NULL, NULL, NULL, NULL),
(37, NULL, NULL, NULL, NULL),
(39, NULL, NULL, NULL, NULL),
(40, 'Valeria', 'Something', NULL, 'Hello'),
(41, NULL, NULL, NULL, NULL),
(42, 'Kian', 'Miri', '67490d6836103.webp', ''),
(43, 'Badge', 'Bunny', '674b27727e1e0.webp', ''),
(44, '2Badge', 'Bunny', '674b35157a6e9.webp', ''),
(45, 'jhg', 'uytr', '674b38ef9ee1e.webp', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_ranks`
--

DROP TABLE IF EXISTS `user_ranks`;
CREATE TABLE `user_ranks` (
  `rank_id` int(11) NOT NULL,
  `rank_name` varchar(50) NOT NULL,
  `min_points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_ranks`
--

INSERT INTO `user_ranks` (`rank_id`, `rank_name`, `min_points`) VALUES
(1, 'Beginner', 0),
(2, 'Intermediate', 100),
(3, 'Advanced', 500),
(4, 'Expert', 1000);

-- --------------------------------------------------------

--
-- Structure for view `cocktail_tag_categories`
--
DROP TABLE IF EXISTS `cocktail_tag_categories`;

DROP VIEW IF EXISTS `cocktail_tag_categories`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `cocktail_tag_categories`  AS SELECT `tags`.`tag_id` AS `tag_id`, `tags`.`name` AS `tag_name`, substring_index(`tags`.`name`,'_',1) AS `tag_category`, substring_index(`tags`.`name`,'_',-1) AS `tag_label` FROM `tags` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_status`
--
ALTER TABLE `account_status`
  ADD PRIMARY KEY (`account_status_id`),
  ADD UNIQUE KEY `status_name` (`status_name`);

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`badge_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `cocktails`
--
ALTER TABLE `cocktails`
  ADD PRIMARY KEY (`cocktail_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `difficulty_id` (`difficulty_id`);

--
-- Indexes for table `cocktail_ingredients`
--
ALTER TABLE `cocktail_ingredients`
  ADD PRIMARY KEY (`cocktail_id`,`ingredient_id`),
  ADD KEY `ingredient_id` (`ingredient_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `cocktail_steps`
--
ALTER TABLE `cocktail_steps`
  ADD PRIMARY KEY (`step_id`),
  ADD KEY `cocktail_id` (`cocktail_id`);

--
-- Indexes for table `cocktail_tags`
--
ALTER TABLE `cocktail_tags`
  ADD PRIMARY KEY (`cocktail_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `cocktail_id` (`cocktail_id`),
  ADD KEY `parent_comment_id` (`parent_comment_id`);

--
-- Indexes for table `difficulty_levels`
--
ALTER TABLE `difficulty_levels`
  ADD PRIMARY KEY (`difficulty_id`),
  ADD UNIQUE KEY `difficulty_name` (`difficulty_name`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`user_id`,`followed_user_id`),
  ADD KEY `followed_user_id` (`followed_user_id`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `ingredient_tags`
--
ALTER TABLE `ingredient_tags`
  ADD PRIMARY KEY (`ingredient_tag_id`),
  ADD UNIQUE KEY `unique_ingredient_tag` (`ingredient_id`,`tag_id`),
  ADD KEY `fk_tag` (`tag_id`);

--
-- Indexes for table `ingredient_unit`
--
ALTER TABLE `ingredient_unit`
  ADD PRIMARY KEY (`unit_id`),
  ADD UNIQUE KEY `unit_name` (`unit_name`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`cocktail_id`),
  ADD KEY `cocktail_id` (`cocktail_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `fk_tag_category` (`tag_category_id`);

--
-- Indexes for table `tag_categories`
--
ALTER TABLE `tag_categories`
  ADD PRIMARY KEY (`tag_category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `trigger_logs`
--
ALTER TABLE `trigger_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `account_status_id` (`account_status_id`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `rank_id` (`rank_id`);

--
-- Indexes for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD PRIMARY KEY (`user_id`,`badge_id`),
  ADD KEY `badge_id` (`badge_id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_ranks`
--
ALTER TABLE `user_ranks`
  ADD PRIMARY KEY (`rank_id`),
  ADD UNIQUE KEY `rank_name` (`rank_name`),
  ADD UNIQUE KEY `min_points` (`min_points`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_status`
--
ALTER TABLE `account_status`
  MODIFY `account_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `badge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cocktails`
--
ALTER TABLE `cocktails`
  MODIFY `cocktail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `cocktail_steps`
--
ALTER TABLE `cocktail_steps`
  MODIFY `step_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=344;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `difficulty_levels`
--
ALTER TABLE `difficulty_levels`
  MODIFY `difficulty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `ingredient_tags`
--
ALTER TABLE `ingredient_tags`
  MODIFY `ingredient_tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT for table `ingredient_unit`
--
ALTER TABLE `ingredient_unit`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `tag_categories`
--
ALTER TABLE `tag_categories`
  MODIFY `tag_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `trigger_logs`
--
ALTER TABLE `trigger_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `user_ranks`
--
ALTER TABLE `user_ranks`
  MODIFY `rank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cocktails`
--
ALTER TABLE `cocktails`
  ADD CONSTRAINT `cocktails_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cocktails_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `cocktails_ibfk_3` FOREIGN KEY (`difficulty_id`) REFERENCES `difficulty_levels` (`difficulty_id`);

--
-- Constraints for table `cocktail_ingredients`
--
ALTER TABLE `cocktail_ingredients`
  ADD CONSTRAINT `cocktail_ingredients_ibfk_1` FOREIGN KEY (`cocktail_id`) REFERENCES `cocktails` (`cocktail_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cocktail_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`),
  ADD CONSTRAINT `cocktail_ingredients_ibfk_3` FOREIGN KEY (`unit_id`) REFERENCES `ingredient_unit` (`unit_id`);

--
-- Constraints for table `cocktail_steps`
--
ALTER TABLE `cocktail_steps`
  ADD CONSTRAINT `cocktail_steps_ibfk_1` FOREIGN KEY (`cocktail_id`) REFERENCES `cocktails` (`cocktail_id`) ON DELETE CASCADE;

--
-- Constraints for table `cocktail_tags`
--
ALTER TABLE `cocktail_tags`
  ADD CONSTRAINT `cocktail_tags_ibfk_1` FOREIGN KEY (`cocktail_id`) REFERENCES `cocktails` (`cocktail_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cocktail_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`cocktail_id`) REFERENCES `cocktails` (`cocktail_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`parent_comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE;

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`followed_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `ingredient_tags`
--
ALTER TABLE `ingredient_tags`
  ADD CONSTRAINT `fk_ingredient` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`cocktail_id`) REFERENCES `cocktails` (`cocktail_id`) ON DELETE CASCADE;

--
-- Constraints for table `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `fk_tag_category` FOREIGN KEY (`tag_category_id`) REFERENCES `tag_categories` (`tag_category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`account_status_id`) REFERENCES `account_status` (`account_status_id`);

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_activity_ibfk_2` FOREIGN KEY (`rank_id`) REFERENCES `user_ranks` (`rank_id`);

--
-- Constraints for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD CONSTRAINT `user_badges_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_badges_ibfk_2` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`badge_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `user_profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
