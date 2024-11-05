-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: database:3306
-- Generation Time: Nov 05, 2024 at 09:12 PM
-- Server version: 10.4.34-MariaDB-1:10.4.34+maria~ubu2004
-- PHP Version: 8.2.22

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

-- --------------------------------------------------------

--
-- Table structure for table `account_status`
--

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

CREATE TABLE `badges` (
  `badge_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `badges`
--

INSERT INTO `badges` (`badge_id`, `name`, `description`) VALUES
(1, 'Master Mixer', 'Awarded for creating 10 or more cocktails.'),
(2, 'Recipe Guru', 'Awarded for receiving 50 likes on your cocktails.'),
(3, 'Rising Star', 'Awarded for uploading 3 new cocktail recipes.'),
(4, 'Tropical Expert', 'Awarded for making 5 tropical cocktails.'),
(5, 'Classic Cocktail Connoisseur', 'Awarded for mastering classic cocktails.');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

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
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cocktails`
--

INSERT INTO `cocktails` (`cocktail_id`, `user_id`, `title`, `description`, `prep_time`, `image`, `category_id`, `difficulty_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mojito Sun', 'A refreshing mint cocktail with lime and rum.', NULL, 'mojito.jpeg', 2, NULL, '2023-06-15 00:00:00', '2024-10-21 22:40:45'),
(2, 2, 'Old Fashioned', 'A whiskey-based cocktail with a hint of citrus.', NULL, 'old_fashioned.jpeg', 1, NULL, '2023-07-20 00:00:00', '2024-10-22 08:53:35'),
(3, 3, 'Margarita', 'A classic tequila cocktail with lime juice and salt.', NULL, 'margarita.jpeg', 2, NULL, '2023-08-05 00:00:00', '0000-00-00 00:00:00'),
(4, 4, 'Negroni', 'A bittersweet cocktail made with gin, Campari, and sweet vermouth.', NULL, 'negroni.jpeg', 1, NULL, '2023-06-25 00:00:00', '0000-00-00 00:00:00'),
(5, 5, 'Pina Colada', 'A tropical cocktail made with rum, coconut cream, and pineapple juice.', NULL, 'pina_colada.jpeg', 2, NULL, '2023-07-30 00:00:00', '0000-00-00 00:00:00'),
(6, 6, 'Daiquiri', 'A rum cocktail with lime and sugar syrup.', NULL, 'classic-daiquiri.webp', 2, NULL, '2023-08-10 00:00:00', '2024-10-22 09:36:32'),
(7, 7, 'Cosmopolitan', 'A vodka cocktail with cranberry juice and triple sec.', NULL, 'cosmopolitan.jpeg', 2, NULL, '2023-06-18 00:00:00', '0000-00-00 00:00:00'),
(8, 8, 'Whiskey Sour', 'A classic cocktail made with whiskey and lemon juice.', NULL, 'whiskey_sour.jpeg', 1, NULL, '2023-08-22 00:00:00', '0000-00-00 00:00:00'),
(9, 9, 'Manhattan', 'A whiskey cocktail with sweet vermouth and bitters.', NULL, 'manhattan.jpeg', 1, NULL, '2023-09-12 00:00:00', '0000-00-00 00:00:00'),
(10, 10, 'Mai Tai', 'A tropical rum cocktail with orange and lime juices.', NULL, 'mai_tai.jpeg', 2, NULL, '2023-06-15 00:00:00', '0000-00-00 00:00:00'),
(11, 11, 'Gin and Tonic', 'A simple with gin and tonic water.', NULL, 'gin_and_tonic.jpeg', 1, NULL, '2023-08-20 00:00:00', '2024-10-22 08:02:55'),
(12, 12, 'Tequila Sunrise', 'A tequila cocktail with orange juice and grenadine.', NULL, 'tequila_sunrise.jpeg', 2, NULL, '2023-07-01 00:00:00', '0000-00-00 00:00:00'),
(13, 13, 'Bloody Mary', 'A vodka cocktail with tomato juice and spices.', NULL, 'bloody_mary.jpeg', 3, NULL, '2023-09-15 00:00:00', '0000-00-00 00:00:00'),
(14, 14, 'Screwdriver', 'A simple vodka cocktail with orange juice.', NULL, 'screwdriver.jpeg', 3, NULL, '2023-07-25 00:00:00', '0000-00-00 00:00:00'),
(15, 15, 'Cuba Libre', 'A rum cocktail with cola and lime juice.', NULL, 'cuba_libre.jpeg', 3, NULL, '2023-08-05 00:00:00', '0000-00-00 00:00:00'),
(16, 1, 'Long Island Iced Tea', 'A strong cocktail with vodka, rum, tequila, gin, and triple sec.', NULL, 'long_island_iced_tea.jpeg', 2, NULL, '2023-09-08 00:00:00', '0000-00-00 00:00:00'),
(17, 2, 'Tom Collins', 'A gin cocktail with lemon juice and soda water.', NULL, 'tom_collins.jpeg', 1, NULL, '2023-07-28 00:00:00', '0000-00-00 00:00:00'),
(18, 3, 'French 75', 'A gin cocktail with lemon juice and champagne.', NULL, 'french_75.jpeg', 1, NULL, '2023-08-15 00:00:00', '0000-00-00 00:00:00'),
(19, 4, 'Caipirinha', 'A Brazilian cocktail made with cachaça, lime, and sugar.', NULL, 'caipirinha.jpeg', 3, NULL, '2023-09-05 00:00:00', '0000-00-00 00:00:00'),
(20, 5, 'Martini', 'A classic gin cocktail with vermouth.', NULL, 'martini.jpeg', 1, NULL, '2023-06-22 00:00:00', '0000-00-00 00:00:00'),
(21, 6, 'Paloma', 'A tequila cocktail with grapefruit soda and lime.', NULL, 'paloma.jpeg', 2, NULL, '2023-09-18 00:00:00', '0000-00-00 00:00:00'),
(22, 7, 'Aperol Spritz', 'A refreshing cocktail with Aperol, Prosecco, and soda water.', NULL, 'aperol_spritz.jpeg', 2, NULL, '2023-08-20 00:00:00', '0000-00-00 00:00:00'),
(23, 8, 'Sazerac', 'A whiskey cocktail with bitters and sugar.', NULL, 'sazerac.jpeg', 1, NULL, '2023-07-12 00:00:00', '0000-00-00 00:00:00'),
(24, 9, 'Moscow Mule', 'A vodka cocktail with ginger beer and lime.', NULL, 'moscow_mule.jpeg', 2, NULL, '2023-06-30 00:00:00', '0000-00-00 00:00:00'),
(25, 10, 'Sidecar', 'A brandy cocktail with triple sec and lemon juice.', NULL, 'sidecar.jpeg', 1, NULL, '2023-08-03 00:00:00', '0000-00-00 00:00:00'),
(26, 11, 'Mint Julep', 'A bourbon cocktail with mint and sugar syrup.', NULL, '', 1, NULL, '2023-09-22 00:00:00', '0000-00-00 00:00:00'),
(27, 12, 'Pisco Sour', 'A South American cocktail with pisco, lime juice, and egg white.', NULL, '', 2, NULL, '2023-07-19 00:00:00', '0000-00-00 00:00:00'),
(28, 13, 'Brandy Alexander', 'A creamy cocktail with brandy and cream.', NULL, '', 3, NULL, '2023-09-25 00:00:00', '0000-00-00 00:00:00'),
(29, 14, 'Rum Punch', 'A rum cocktail with pineapple juice and grenadine.', NULL, '', 2, NULL, '2023-08-02 00:00:00', '0000-00-00 00:00:00'),
(30, 15, 'Gin Fizz', 'A gin cocktail with lemon juice and soda water.', NULL, '', 1, NULL, '2023-06-17 00:00:00', '0000-00-00 00:00:00'),
(31, 26, 'Classic Daiquiri', 'One of the most classic sour cocktails! This daiquiri recipe has the perfect balance of boozy, tart and sweet with rum, lime and sweetener.', NULL, 'classic-daiquiri.webp', 5, NULL, '2024-10-17 12:53:32', '2024-10-17 19:11:19'),
(32, 26, 'Amaretto Sour', 'An amaretto sour a classic sour cocktail made with amaretto liqueur.', NULL, NULL, 5, 1, '2024-10-17 13:32:38', '2024-10-21 16:35:17'),
(33, 26, 'Frozen Strawberry Daiquiri', 'Strawberry Daiquiris are everything that comes to mind when I think of cocktails:', NULL, 'Strawberry-Daiquiri.webp', 3, 1, '2024-10-17 16:49:00', '2024-10-21 16:59:22'),
(34, 26, 'Frozen Strawberry Daiquiri', 'Strawberry Daiquiris are everything that comes to mind when I think of cocktails:', NULL, 'Strawberry-Daiquiri.webp', 3, 1, '2024-10-17 16:52:03', '2024-10-22 18:13:28'),
(35, 21, 'Rhubarb Gin', 'Use seasonal rhubarb to make this G&T-with-a-difference, or top the finished gin with soda water for a refreshing and gloriously pink summertime drink', NULL, 'rhubarb-gin.webp', 8, 1, '2024-10-22 11:05:32', '0000-00-00 00:00:00'),
(37, 21, 'Woo Woo', 'Mix vodka, peach schnapps, cranberry juice and fresh lime to make this perfect party cocktail, garnished with a lime wedge (and maybe a tiny umbrella too)', NULL, 'woo-woo.webp', 5, 2, '2024-10-22 11:20:05', '0000-00-00 00:00:00'),
(39, 39, 'Banana Daiquiri', 'Bring sunshine to your cocktail repertoire in the form of a punchy, fun banana daiquiri. Garnish with banana slices, lime wedges or cocktail cherries', NULL, 'Banana-daiquiri.webp', 6, 2, '2024-10-22 17:57:12', '0000-00-00 00:00:00'),
(40, 22, 'Mojito', 'Some descritptiion', NULL, 'mojito.webp', 8, 2, '2024-10-28 15:45:49', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `cocktail_ingredients`
--

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
(1, 1, 50.00, 1),
(1, 14, 100.00, 1),
(1, 48, 25.00, 1),
(2, 5, 60.00, 1),
(2, 18, 2.00, 3),
(2, 45, 1.00, 7),
(3, 3, 40.00, 1),
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
(21, 3, 50.00, 1),
(21, 6, 20.00, 2),
(21, 20, 100.00, 7),
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
(37, 1, 50.00, 1),
(37, 48, 25.00, 1),
(39, 6, 10.00, 1),
(39, 41, 50.00, 1),
(39, 52, 25.00, 1),
(40, 8, 5.00, 2),
(40, 41, 30.00, 3);

-- --------------------------------------------------------

--
-- Table structure for table `cocktail_steps`
--

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
(7, 3, 1, 'Rim the glass with salt and set aside.'),
(8, 3, 2, 'Shake tequila, lime juice, and triple sec with ice.'),
(9, 3, 3, 'Strain into the prepared glass and garnish with a lime wedge.'),
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
(54, 32, 1, 'Add amaretto, bourbon, lemon juice, simple syrup and egg white to a shaker and dry-shake (no ice) for 15 seconds.'),
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
(244, 39, 1, 'Put the rum, banana liqueur, lime juice, banana and ice in a blender suitable for crushing ice, and blitz until smooth.'),
(245, 39, 2, 'Pour the drink into a hurricane or other tall glass, and garnish with banana slices or chips, lime wedges or cocktail cherries, if you like (we skewered two cocktail cherries and a lime wedge on a cocktail stick)'),
(246, 34, 1, 'Test this step\r\n'),
(247, 40, 1, 'Test Test ');

-- --------------------------------------------------------

--
-- Table structure for table `cocktail_tags`
--

CREATE TABLE `cocktail_tags` (
  `cocktail_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cocktail_tags`
--

INSERT INTO `cocktail_tags` (`cocktail_id`, `tag_id`) VALUES
(1, 5),
(2, 8),
(3, 4),
(4, 8),
(5, 2),
(6, 5),
(7, 4),
(8, 3),
(9, 8),
(10, 2),
(11, 9),
(12, 4),
(13, 7),
(14, 4),
(15, 3),
(16, 3),
(17, 5),
(18, 10),
(19, 5),
(20, 9),
(21, 4),
(22, 5),
(23, 8),
(24, 5),
(25, 3),
(26, 9),
(27, 4),
(28, 6),
(29, 2),
(30, 5);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

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
(17, 26, 2, NULL, 'OLD IS GOLD', '2024-10-22 22:30:06');

-- --------------------------------------------------------

--
-- Table structure for table `difficulty_levels`
--

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
(26, 6, '2024-11-01 11:41:24'),
(26, 26, '2024-11-01 11:39:18');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`ingredient_id`, `name`) VALUES
(29, '2'),
(32, '29'),
(33, '30'),
(34, '31'),
(35, '32'),
(36, '33'),
(37, '34'),
(38, '35'),
(39, '36'),
(40, '37'),
(42, '41'),
(43, '42'),
(44, '43'),
(30, '6'),
(31, '8'),
(22, 'Aperol'),
(52, 'Banana Liqueur '),
(18, 'Bitters'),
(25, 'Brandy'),
(17, 'Campari'),
(47, 'Caster Sugar'),
(11, 'Coconut Cream'),
(14, 'Cranberry Juice'),
(28, 'Cream'),
(27, 'Egg White'),
(4, 'Gin'),
(24, 'Ginger Beer'),
(20, 'Grapefruit Soda'),
(51, 'Ice'),
(10, 'Ice Cubes'),
(7, 'Lemon Juice'),
(6, 'Lime Juice'),
(8, 'Mint Leaves'),
(45, 'Orange'),
(13, 'Orange Juice'),
(48, 'Peach Schnaps'),
(12, 'Pineapple Juice'),
(46, 'Pink Rhubarb Stalks'),
(26, 'Pisco'),
(21, 'Prosecco'),
(2, 'Rum'),
(23, 'Soda Water'),
(19, 'Spices'),
(9, 'Sugar Syrup'),
(16, 'Sweet Vermouth'),
(3, 'Tequila'),
(15, 'Triple Sec'),
(1, 'Vodka'),
(50, 'Watermelon'),
(49, 'Watermelon Liqueur'),
(5, 'Whiskey'),
(41, 'White Rum');

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_unit`
--

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
(132, 26, 39, '2024-10-29 10:50:10'),
(134, 26, 20, '2024-10-29 10:52:07'),
(135, 26, 19, '2024-10-29 10:53:21'),
(136, 26, 17, '2024-10-29 10:59:14'),
(137, 26, 31, '2024-11-01 16:27:17'),
(138, 26, 32, '2024-11-01 20:21:06');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tag_id`, `name`) VALUES
(1, 'Citrus'),
(8, 'Classic'),
(6, 'Creamy'),
(4, 'Fruity'),
(9, 'Herbal'),
(5, 'Refreshing'),
(7, 'Spicy'),
(3, 'Strong'),
(10, 'Sweet'),
(2, 'Tropical');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

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
(21, 'admin_user', 'admin@example.com', '$2y$10$kiJxtnUuP4XZtcO6WOWLa.J1l4Aq5HAgPkPqxu38r54DOVlZV6KGq', 1, '2023-12-01 00:00:00', '2024-10-22 16:35:05', 1),
(22, 'joninaab', 'joninaa@live.com', '$2y$10$NnfzvCmJVuqY5RYhDirT4uR4Ndi6aQzVaC.6Gyjw5KDasZqXRzexy', 1, '2024-10-15 13:50:32', '2024-11-01 10:40:19', 0),
(24, 'irena', 'irena@hello.is', '$2y$10$8zrxhicjgoANGWzi1CuJ.OlkLn0eE8z3wZ8WlTRHCxc6Ok2SHu22i', 1, '2024-10-15 13:59:35', '2024-10-15 20:47:27', 0),
(25, 'Jonina', 'jonina@hello.is', '$2y$10$ZWHBI1MUR7TVH6nXBMDv2umpjQ/2kwjdEjkKGEVmZQbiE5rw56DVO', 1, '2024-10-16 10:27:42', NULL, 0),
(26, 'Kian', 'kian@hello.is', '$2y$10$1KEclIW2ecNk8o27IrOKPOC2gr3Z4lTWxJYwvUm6tv3nwgiANTA/y', 1, '2024-10-16 10:30:51', '2024-11-01 11:13:54', 0),
(27, 'Jon', 'jon@hello.is', '$2y$10$RSKGVqTvNUzeKAzvQXPilO3hu/8vWZsHbRd2VHs5tHoI6TIgr82.m', 1, '2024-10-16 10:34:56', NULL, 0),
(32, 'JonFreyr', 'jonfreyr@hello.is', '$2y$10$7TCawSbsceQ/PcuCD2OO6upTXOe6YitAS6CUH7z/5dUxTWHqvoJji', 1, '2024-10-16 11:28:30', NULL, 0),
(33, 'JonF', 'jonf@hello.is', '$2y$10$4WvrqMMABbb64UPU9Rx8teRCBjnxr0sXpkrKR7yg7CVigZfYJpTmC', 1, '2024-10-16 11:31:11', NULL, 0),
(35, 'Kian23', 'kian23@hello.id', '$2y$10$BwwM1vydTBCFzTo5Lea5.u4s1fwW96f.7J9JaP6o6rqi6tG1bPMyu', 1, '2024-10-16 13:06:32', NULL, 0),
(37, 'JoninaBjarna', 'joninabjarna@hello.is', '$2y$10$jikprUMFsS5L9kyIP4j0Au13eVDaPo8DmAGkmsRRHpO4tZgfvS1g2', 1, '2024-10-16 19:06:44', '2024-10-16 19:06:55', 0),
(39, 'JonFreyrS', 'jonfs@hello.is', '$2y$10$JIQXPCuQGAat51HoEtN9bOYJBm5iMDGJim09RirD84ZKEUUA3k3lu', 1, '2024-10-22 17:41:16', '2024-10-22 17:41:28', 0),
(40, 'Val', 'val@hello.is', '$2y$10$eYaljXNQOVHPqnP.njEbRO570nMpS5Vekmhz8ynZ9MWfrD4etW/HC', 1, '2024-11-01 10:49:05', '2024-11-01 10:49:45', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

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
(1, 1, 150, 5, 16),
(2, 2, 220, 7, 19),
(3, 1, 130, 4, 13),
(4, 3, 350, 12, 42),
(5, 1, 90, 3, 6),
(6, 1, 110, 4, 10),
(7, 1, 105, 3, 10),
(8, 2, 210, 6, 19),
(9, 1, 95, 2, 7),
(10, 1, 120, 3, 12),
(11, 1, 100, 3, 8),
(12, 2, 200, 6, 15),
(13, 2, 195, 5, 13),
(14, 3, 340, 11, 38),
(15, 1, 85, 2, 5),
(16, 2, 210, 6, 17),
(17, 1, 110, 4, 9),
(18, 2, 205, 5, 12),
(19, 1, 115, 4, 8),
(20, 3, 330, 10, 35);

-- --------------------------------------------------------

--
-- Table structure for table `user_badges`
--

CREATE TABLE `user_badges` (
  `user_id` int(11) NOT NULL,
  `badge_id` int(11) NOT NULL,
  `earned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_badges`
--

INSERT INTO `user_badges` (`user_id`, `badge_id`, `earned_at`) VALUES
(1, 1, '2023-08-10 00:00:00'),
(2, 2, '2023-09-05 00:00:00'),
(3, 3, '2023-07-15 00:00:00'),
(4, 1, '2023-06-20 00:00:00'),
(5, 3, '2023-05-30 00:00:00'),
(6, 2, '2023-08-01 00:00:00'),
(7, 3, '2023-07-20 00:00:00'),
(8, 1, '2023-08-22 00:00:00'),
(9, 3, '2023-09-10 00:00:00'),
(10, 2, '2023-06-11 00:00:00'),
(11, 1, '2023-07-25 00:00:00'),
(12, 3, '2023-09-19 00:00:00'),
(13, 1, '2023-08-30 00:00:00'),
(14, 2, '2023-10-05 00:00:00'),
(15, 3, '2023-09-20 00:00:00'),
(16, 1, '2023-06-14 00:00:00'),
(17, 2, '2023-08-11 00:00:00'),
(18, 3, '2023-09-03 00:00:00'),
(19, 1, '2023-07-28 00:00:00'),
(20, 2, '2023-08-19 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

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
(1, 'John', 'Doe', '/uploads/users/john_doe.jpg', 'Loves classic cocktails and experimenting with flavors. A whiskey lover at heart.'),
(2, 'Jane', 'Smith', '/uploads/users/jane_smith.jpg', 'A mixologist in training with a passion for tequila.'),
(3, 'Emily', 'Clark', '/uploads/users/emily_clark.jpg', 'I enjoy crafting cocktails with fresh ingredients and unique twists.'),
(4, 'William', 'Jones', '/uploads/users/william_jones.jpg', 'Whiskey enthusiast and cocktail purist. Fan of Old Fashioned.'),
(5, 'Mary', 'Johnson', '/uploads/users/mary_johnson.jpg', 'Amateur bartender, always experimenting with new recipes.'),
(6, 'Alex', 'Brown', '/uploads/users/alex_brown.jpg', 'Cocktail lover and part-time bartender.'),
(7, 'Sarah', 'White', '/uploads/users/sarah_white.jpg', 'Passionate about all things tropical and fruity.'),
(8, 'Michael', 'Smith', '/uploads/users/michael_smith.jpg', 'Crafting signature cocktails and exploring new recipes.'),
(9, 'Linda', 'Garcia', '/uploads/users/linda_garcia.jpg', 'Home bartender sharing fun and easy cocktail recipes.'),
(10, 'David', 'Miller', '/uploads/users/david_miller.jpg', 'Experimenting with strong, bitter cocktails.'),
(11, 'Laura', 'Davis', '/uploads/users/laura_davis.jpg', 'Loves making easy cocktails for home parties.'),
(12, 'James', 'Wilson', '/uploads/users/james_wilson.jpg', 'I create cocktails with a focus on balance and flavor.'),
(13, 'Karen', 'Taylor', '/uploads/users/karen_taylor.jpg', 'Tequila and margarita enthusiast.'),
(14, 'Daniel', 'Moore', '/uploads/users/daniel_moore.jpg', 'Exploring craft cocktails and unique mixers.'),
(15, 'Patricia', 'Anderson', '/uploads/users/patricia_anderson.jpg', 'Creating cocktails inspired by global flavors.'),
(16, 'Robert', 'Thomas', '/uploads/users/robert_thomas.jpg', 'Gin and tonic master, experimenting with different botanicals.'),
(17, 'Barbara', 'Jackson', '/uploads/users/barbara_jackson.jpg', 'Creating fun, party-friendly cocktails.'),
(18, 'Charles', 'Martin', '/uploads/users/charles_martin.jpg', 'Making cocktails that are simple and delicious.'),
(19, 'Susan', 'Lee', '/uploads/users/susan_lee.jpg', 'Enthusiastic about fruity, tropical cocktails.'),
(20, 'Paul', 'Walker', '/uploads/users/paul_walker.jpg', 'Always searching for the perfect rum cocktail.'),
(40, 'Valeria', 'Something', NULL, 'Hello');

-- --------------------------------------------------------

--
-- Table structure for table `user_ranks`
--

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
  ADD UNIQUE KEY `name` (`name`);

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
  MODIFY `badge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cocktails`
--
ALTER TABLE `cocktails`
  MODIFY `cocktail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `cocktail_steps`
--
ALTER TABLE `cocktail_steps`
  MODIFY `step_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=248;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `difficulty_levels`
--
ALTER TABLE `difficulty_levels`
  MODIFY `difficulty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `ingredient_unit`
--
ALTER TABLE `ingredient_unit`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

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
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`cocktail_id`) REFERENCES `cocktails` (`cocktail_id`) ON DELETE CASCADE;

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
