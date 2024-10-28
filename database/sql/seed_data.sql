USE drinx_db;

-- 1. Insert data into `account_status`
INSERT INTO account_status (account_status_id, status_name)
VALUES
    (1, 'Active'),
    (2, 'Suspended'),
    (3, 'Banned');

-- 2. Insert data into user_ranks
INSERT INTO user_ranks (rank_id, rank_name, min_points)
VALUES
    (1, 'Beginner', 0),
    (2, 'Intermediate', 100),
    (3, 'Advanced', 500),
    (4, 'Expert', 1000);

-- 3. Insert data into `badges`
INSERT INTO badges (badge_id, name, description)
VALUES
    (1, 'Master Mixer', 'Awarded for creating 10 or more cocktails.'),
    (2, 'Recipe Guru', 'Awarded for receiving 50 likes on your cocktails.'),
    (3, 'Rising Star', 'Awarded for uploading 3 new cocktail recipes.'),
    (4, 'Tropical Expert', 'Awarded for making 5 tropical cocktails.'),
    (5, 'Classic Cocktail Connoisseur', 'Awarded for mastering classic cocktails.');

-- 4. Insert data into `categories`
INSERT INTO categories (category_id, name)
VALUES
    (1, 'Classic Cocktails'),
    (2, 'Tropical Cocktails'),
    (3, 'Fruity Cocktails'),
    (4, 'Strong Cocktails'),
    (5, 'Refreshing Cocktails'),
    (6, 'Creamy Cocktails'),
    (7, 'Whiskey Cocktails'),
    (8, 'Gin Cocktails'),
    (9, 'Tequila Cocktails'),
    (10, 'Vodka Cocktails');

-- 5. Insert data into `difficulty_levels`
INSERT INTO difficulty_levels (difficulty_id, difficulty_name)
VALUES
    (1, 'Easy'),
    (2, 'Medium'),
    (3, 'Hard');

-- 6. Insert data into ìngredients`
INSERT INTO ingredients (ingredient_id, name)
VALUES
    (1, 'Vodka'),
    (2, 'Rum'),
    (3, 'Tequila'),
    (4, 'Gin'),
    (5, 'Whiskey'),
    (6, 'Lime Juice'),
    (7, 'Lemon Juice'),
    (8, 'Mint Leaves'),
    (9, 'Sugar Syrup'),
    (10, 'Ice Cubes'),
    (11, 'Coconut Cream'),
    (12, 'Pineapple Juice'),
    (13, 'Orange Juice'),
    (14, 'Cranberry Juice'),
    (15, 'Triple Sec'),
    (16, 'Sweet Vermouth'),
    (17, 'Campari'),
    (18, 'Bitters'),
    (19, 'Spices'),
    (20, 'Grapefruit Soda'),
    (21, 'Prosecco'),
    (22, 'Aperol'),
    (23, 'Soda Water'),
    (24, 'Ginger Beer'),
    (25, 'Brandy'),
    (26, 'Pisco'),
    (27, 'Egg White'),
    (28, 'Cream');

-- 7. Insert data into `ingredient_unit`
INSERT INTO ingredient_unit (unit_id, unit_name)
VALUES
    (1, 'ml'),
    (2, 'oz'),
    (3, 'cl'),
    (4, 'tsp'),
    (5, 'dash'),
    (6, 'cup'),
    (7, 'piece');

-- 9. Insert data into `users`
INSERT INTO users (user_id, username, email, password, join_date, account_status_id, is_admin)
VALUES
    (1, 'john_doe', 'john.doe@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-01-10', 1, 0),
    (2, 'jane_smith', 'jane.smith@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-02-15', 1, 0),
    (3, 'emily_clark', 'emily.clark@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-03-12', 1, 0),
    (4, 'william_jones', 'william.jones@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-04-22', 1, 0),
    (5, 'mary_johnson', 'mary.johnson@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-05-30', 2, 0),  -- suspended user
    (6, 'alex_brown', 'alex.brown@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-06-05', 1, 0),
    (7, 'sarah_white', 'sarah.white@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-07-10', 1, 0),
    (8, 'michael_smith', 'michael.smith@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-08-15', 1, 0),
    (9, 'linda_garcia', 'linda.garcia@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-09-20', 1, 0),
    (10, 'david_miller', 'david.miller@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-10-25', 1, 0),
    (11, 'laura_davis', 'laura.davis@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-11-02', 1, 0),
    (12, 'james_wilson', 'james.wilson@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-11-10', 1, 0),
    (13, 'karen_taylor', 'karen.taylor@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-12-15', 1, 0),
    (14, 'daniel_moore', 'daniel.moore@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-01-05', 1, 0),
    (15, 'patricia_anderson', 'patricia.anderson@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-02-12', 1, 0),
    (16, 'robert_thomas', 'robert.thomas@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-03-20', 1, 0),
    (17, 'barbara_jackson', 'barbara.jackson@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-04-15', 2, 0),  -- suspended user
    (18, 'charles_martin', 'charles.martin@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-05-18', 1, 0),
    (19, 'susan_lee', 'susan.lee@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-06-25', 1, 0),
    (20, 'paul_walker', 'paul.walker@example.com', '$2y$10$vGcIaFUZVUuWwZbbqnwtN.YSFYb5V0R0Kgjz0C09Xw.tMs/RZNJke', '2023-07-30', 3, 0);  -- banned user
-- 10.  Insert data into `user_profile`

-- 8. Admin User with `is_admin` flag - password is '12345678
INSERT INTO users (user_id, username, email, password, join_date, account_status_id, is_admin)
VALUES
    (21, 'admin_user', 'admin@example.com', '$2y$10$abcdefg1234567890hijklmnopqrstuvwxyzabcdefg1234567890', '2023-12-01', 1, 1);  -- is_admin set to 1 for true
INSERT INTO user_profile (user_id, first_name, last_name, profile_picture, bio)
VALUES
    (1, 'John', 'Doe', '/uploads/users/john_doe.jpeg
    ', 'Loves classic cocktails and experimenting with flavors. A whiskey lover at heart.'),
    (2, 'Jane', 'Smith', '/uploads/users/jane_smith.jpeg
    ', 'A mixologist in training with a passion for tequila.'),
    (3, 'Emily', 'Clark', '/uploads/users/emily_clark.jpeg
    ', 'I enjoy crafting cocktails with fresh ingredients and unique twists.'),
    (4, 'William', 'Jones', '/uploads/users/william_jones.jpeg
    ', 'Whiskey enthusiast and cocktail purist. Fan of Old Fashioned.'),
    (5, 'Mary', 'Johnson', '/uploads/users/mary_johnson.jpeg
    ', 'Amateur bartender, always experimenting with new recipes.'),
    (6, 'Alex', 'Brown', '/uploads/users/alex_brown.jpeg
    ', 'Cocktail lover and part-time bartender.'),
    (7, 'Sarah', 'White', '/uploads/users/sarah_white.jpeg
    ', 'Passionate about all things tropical and fruity.'),
    (8, 'Michael', 'Smith', '/uploads/users/michael_smith.jpeg
    ', 'Crafting signature cocktails and exploring new recipes.'),
    (9, 'Linda', 'Garcia', '/uploads/users/linda_garcia.jpeg
    ', 'Home bartender sharing fun and easy cocktail recipes.'),
    (10, 'David', 'Miller', '/uploads/users/david_miller.jpeg
    ', 'Experimenting with strong, bitter cocktails.'),
    (11, 'Laura', 'Davis', '/uploads/users/laura_davis.jpeg
    ', 'Loves making easy cocktails for home parties.'),
    (12, 'James', 'Wilson', '/uploads/users/james_wilson.jpeg
    ', 'I create cocktails with a focus on balance and flavor.'),
    (13, 'Karen', 'Taylor', '/uploads/users/karen_taylor.jpeg
    ', 'Tequila and margarita enthusiast.'),
    (14, 'Daniel', 'Moore', '/uploads/users/daniel_moore.jpeg
    ', 'Exploring craft cocktails and unique mixers.'),
    (15, 'Patricia', 'Anderson', '/uploads/users/patricia_anderson.jpeg
    ', 'Creating cocktails inspired by global flavors.'),
    (16, 'Robert', 'Thomas', '/uploads/users/robert_thomas.jpeg
    ', 'Gin and tonic master, experimenting with different botanicals.'),
    (17, 'Barbara', 'Jackson', '/uploads/users/barbara_jackson.jpeg
    ', 'Creating fun, party-friendly cocktails.'),
    (18, 'Charles', 'Martin', '/uploads/users/charles_martin.jpeg
    ', 'Making cocktails that are simple and delicious.'),
    (19, 'Susan', 'Lee', '/uploads/users/susan_lee.jpeg
    ', 'Enthusiastic about fruity, tropical cocktails.'),
    (20, 'Paul', 'Walker', '/uploads/users/paul_walker.jpeg
    ', 'Always searching for the perfect rum cocktail.');

-- 11. Insert data into `user_activity`
INSERT INTO user_activity (user_id, rank_id, points, cocktails_uploaded, likes_received)
VALUES
    (1, 1, 150, 5, 12),
    (2, 2, 220, 7, 20),
    (3, 1, 130, 4, 10),
    (4, 3, 350, 12, 40),
    (5, 1, 90, 3, 5),
    (6, 1, 110, 4, 9),
    (7, 1, 105, 3, 8),
    (8, 2, 210, 6, 18),
    (9, 1, 95, 2, 6),
    (10, 1, 120, 3, 10),
    (11, 1, 100, 3, 7),
    (12, 2, 200, 6, 15),
    (13, 2, 195, 5, 13),
    (14, 3, 340, 11, 38),
    (15, 1, 85, 2, 5),
    (16, 2, 210, 6, 17),
    (17, 1, 110, 4, 9),
    (18, 2, 205, 5, 12),
    (19, 1, 115, 4, 8),
    (20, 3, 330, 10, 35);

-- 12. Insert data into `user_badges`
INSERT INTO user_badges (user_id, badge_id, earned_at)
VALUES
    (1, 1, '2023-08-10'),
    (2, 2, '2023-09-05'),
    (3, 3, '2023-07-15'),
    (4, 1, '2023-06-20'),
    (5, 3, '2023-05-30'),
    (6, 2, '2023-08-01'),
    (7, 3, '2023-07-20'),
    (8, 1, '2023-08-22'),
    (9, 3, '2023-09-10'),
    (10, 2, '2023-06-11'),
    (11, 1, '2023-07-25'),
    (12, 3, '2023-09-19'),
    (13, 1, '2023-08-30'),
    (14, 2, '2023-10-05'),
    (15, 3, '2023-09-20'),
    (16, 1, '2023-06-14'),
    (17, 2, '2023-08-11'),
    (18, 3, '2023-09-03'),
    (19, 1, '2023-07-28'),
    (20, 2, '2023-08-19');

-- 13. Insert data into `cocktails`
INSERT INTO cocktails (user_id, title, description, image, category_id, created_at)
VALUES
    (1, 'Mojito', 'A refreshing mint cocktail with lime and rum.', 'mojito.jpeg
    ', 2, '2023-06-15'),
    (2, 'Old Fashioned', 'A whiskey-based cocktail with a hint of citrus.', 'old_fashioned.jpeg
    ', 1, '2023-07-20'),
    (3, 'Margarita', 'A classic tequila cocktail with lime juice and salt.', 'margarita.jpeg
    ', 2, '2023-08-05'),
    (4, 'Negroni', 'A bittersweet cocktail made with gin, Campari, and sweet vermouth.', 'negroni.jpeg
    ', 1, '2023-06-25'),
    (5, 'Pina Colada', 'A tropical cocktail made with rum, coconut cream, and pineapple juice.', 'pina_colada.jpeg
    ', 2, '2023-07-30'),
    (6, 'Daiquiri', 'A rum cocktail with lime and sugar syrup.', 'daiquiri.jpeg
    ', 2, '2023-08-10'),
    (7, 'Cosmopolitan', 'A vodka cocktail with cranberry juice and triple sec.', 'cosmopolitan.jpeg
    ', 2, '2023-06-18'),
    (8, 'Whiskey Sour', 'A classic cocktail made with whiskey and lemon juice.', 'whiskey_sour.jpeg
    ', 1, '2023-08-22'),
    (9, 'Manhattan', 'A whiskey cocktail with sweet vermouth and bitters.', 'manhattan.jpeg
    ', 1, '2023-09-12'),
    (10, 'Mai Tai', 'A tropical rum cocktail with orange and lime juices.', 'mai_tai.jpeg
    ', 2, '2023-06-15'),
    (11, 'Gin and Tonic', 'A simple cocktail with gin and tonic water.', 'gin_and_tonic.jpeg
    ', 1, '2023-08-20'),
    (12, 'Tequila Sunrise', 'A tequila cocktail with orange juice and grenadine.', 'tequila_sunrise.jpeg
    ', 2, '2023-07-01'),
    (13, 'Bloody Mary', 'A vodka cocktail with tomato juice and spices.', 'bloody_mary.jpeg
    ', 3, '2023-09-15'),
    (14, 'Screwdriver', 'A simple vodka cocktail with orange juice.', 'screwdriver.jpeg
    ', 3, '2023-07-25'),
    (15, 'Cuba Libre', 'A rum cocktail with cola and lime juice.', 'cuba_libre.jpeg
    ', 3, '2023-08-05'),
    (1, 'Long Island Iced Tea', 'A strong cocktail with vodka, rum, tequila, gin, and triple sec.', 'long_island_iced_tea.jpeg
    ', 2, '2023-09-08'),
    (2, 'Tom Collins', 'A gin cocktail with lemon juice and soda water.', 'tom_collins.jpeg
    ', 1, '2023-07-28'),
    (3, 'French 75', 'A gin cocktail with lemon juice and champagne.', 'french_75.jpeg
    ', 1, '2023-08-15'),
    (4, 'Caipirinha', 'A Brazilian cocktail made with cachaça, lime, and sugar.', 'caipirinha.jpeg
    ', 3, '2023-09-05'),
    (5, 'Martini', 'A classic gin cocktail with vermouth.', 'martini.jpeg
    ', 1, '2023-06-22'),
    (6, 'Paloma', 'A tequila cocktail with grapefruit soda and lime.', 'paloma.jpeg
    ', 2, '2023-09-18'),
    (7, 'Aperol Spritz', 'A refreshing cocktail with Aperol, Prosecco, and soda water.', 'aperol_spritz.jpeg
    ', 2, '2023-08-20'),
    (8, 'Sazerac', 'A whiskey cocktail with bitters and sugar.', 'sazerac.jpeg
    ', 1, '2023-07-12'),
    (9, 'Moscow Mule', 'A vodka cocktail with ginger beer and lime.', 'moscow_mule.jpeg
    ', 2, '2023-06-30'),
    (10, 'Sidecar', 'A brandy cocktail with triple sec and lemon juice.', 'sidecar.jpeg
    ', 1, '2023-08-03'),
    (11, 'Mint Julep', 'A bourbon cocktail with mint and sugar syrup.', 'mint_julep.jpeg
    ', 1, '2023-09-22'),
    (12, 'Pisco Sour', 'A South American cocktail with pisco, lime juice, and egg white.', 'pisco_sour.jpeg
    ', 2, '2023-07-19'),
    (13, 'Brandy Alexander', 'A creamy cocktail with brandy and cream.', 'brandy_alexander.jpeg
    ', 3, '2023-09-25'),
    (14, 'Rum Punch', 'A rum cocktail with pineapple juice and grenadine.', 'rum_punch.jpeg
    ', 2, '2023-08-02'),
    (15, 'Gin Fizz', 'A gin cocktail with lemon juice and soda water.', 'gin_fizz.jpeg
    ', 1, '2023-06-17');

-- 14. Seed Data for `cocktail_ingredients`
INSERT INTO cocktail_ingredients (cocktail_id, ingredient_id, quantity, unit_id)
VALUES
    (1, 2, '50', 1),  -- Mojito with Rum
    (1, 6, '20', 2),  -- Mojito with Lime Juice
    (1, 8, '5', 3),   -- Mojito with Mint Leaves
    (2, 5, '60', 1),  -- Old Fashioned with Whiskey
    (2, 18, '2', 3),  -- Old Fashioned with Bitters
    (3, 3, '40', 1),  -- Margarita with Tequila
    (3, 6, '20', 2),  -- Margarita with Lime Juice
    (4, 4, '30', 1),  -- Negroni with Gin
    (4, 16, '30', 1), -- Negroni with Sweet Vermouth
    (4, 17, '30', 1), -- Negroni with Campari
    (5, 2, '45', 1),  -- Pina Colada with Rum
    (5, 11, '60', 2), -- Pina Colada with Coconut Cream
    (5, 12, '60', 2), -- Pina Colada with Pineapple Juice
    (6, 2, '50', 1),  -- Daiquiri with Rum
    (6, 6, '20', 2),  -- Daiquiri with Lime Juice
    (6, 9, '10', 3),  -- Daiquiri with Sugar Syrup
    (7, 1, '40', 1),  -- Cosmopolitan with Vodka
    (7, 14, '30', 1), -- Cosmopolitan with Cranberry Juice
    (7, 15, '20', 1), -- Cosmopolitan with Triple Sec
    (8, 5, '50', 1),  -- Whiskey Sour with Whiskey
    (8, 7, '20', 2),  -- Whiskey Sour with Lemon Juice
    (8, 9, '10', 3),  -- Whiskey Sour with Sugar Syrup
    (9, 5, '50', 1),  -- Manhattan with Whiskey
    (9, 16, '30', 1), -- Manhattan with Sweet Vermouth
    (9, 18, '2', 3),  -- Manhattan with Bitters
    (10, 2, '50', 1), -- Mai Tai with Rum
    (10, 6, '20', 2), -- Mai Tai with Lime Juice
    (10, 13, '20', 2), -- Mai Tai with Orange Juice
    (11, 4, '40', 1), -- Gin and Tonic with Gin
    (11, 10, '100', 7), -- Gin and Tonic with Ice Cubes
    (12, 3, '50', 1), -- Tequila Sunrise with Tequila
    (12, 13, '60', 2), -- Tequila Sunrise with Orange Juice
    (12, 19, '10', 3), -- Tequila Sunrise with Grenadine
    (13, 1, '40', 1), -- Bloody Mary with Vodka
    (13, 13, '100', 7), -- Bloody Mary with Tomato Juice
    (13, 19, '10', 3), -- Bloody Mary with Spices
    (14, 1, '40', 1), -- Screwdriver with Vodka
    (14, 14, '100', 7), -- Screwdriver with Orange Juice
    (15, 2, '50', 1), -- Cuba Libre with Rum
    (15, 10, '100', 7), -- Cuba Libre with Ice Cubes
    (15, 6, '20', 2), -- Cuba Libre with Lime Juice
    (16, 1, '30', 1), -- Long Island Iced Tea with Vodka
    (16, 2, '30', 1), -- Long Island Iced Tea with Rum
    (16, 3, '30', 1), -- Long Island Iced Tea with Tequila
    (16, 4, '30', 1), -- Long Island Iced Tea with Gin
    (16, 15, '30', 1), -- Long Island Iced Tea with Triple Sec
    (16, 7, '20', 2), -- Long Island Iced Tea with Lemon Juice
    (16, 10, '100', 7), -- Long Island Iced Tea with Ice Cubes
    (17, 4, '40', 1), -- Tom Collins with Gin
    (17, 7, '20', 2), -- Tom Collins with Lemon Juice
    (17, 9, '10', 3), -- Tom Collins with Sugar Syrup
    (17, 10, '100', 7), -- Tom Collins with Ice Cubes
    (18, 4, '30', 1), -- French 75 with Gin
    (18, 7, '20', 2), -- French 75 with Lemon Juice
    (18, 18, '2', 3), -- French 75 with Champagne
    (19, 3, '50', 1), -- Caipirinha with Cachaça
    (19, 6, '20', 2), -- Caipirinha with Lime
    (19, 9, '10', 3), -- Caipirinha with Sugar
    (20, 4, '60', 1), -- Martini with Gin
    (20, 16, '30', 1), -- Martini with Vermouth
    (21, 3, '50', 1), -- Paloma with Tequila
    (21, 20, '100', 7), -- Paloma with Ice Cubes
    (21, 21, '20', 2), -- Paloma with Grapefruit Soda
    (21, 6, '20', 2), -- Paloma with Lime Juice
    (22, 22, '30', 1), -- Aperol Spritz with Aperol
    (22, 23, '60', 2), -- Aperol Spritz with Prosecco
    (22, 15, '20', 1), -- Aperol Spritz with Soda Water
    (22, 6, '20', 2), -- Aperol Spritz with Orange Slice
    (23, 5, '50', 1), -- Sazerac with Whiskey
    (23, 9, '10', 3), -- Sazerac with Sugar
    (23, 18, '2', 3), -- Sazerac with Bitters
    (24, 1, '40', 1), -- Moscow Mule with Vodka
    (24, 24, '100', 7), -- Moscow Mule with Ice Cubes
    (24, 25, '20', 2), -- Moscow Mule with Ginger Beer
    (24, 6, '20', 2), -- Moscow Mule with Lime Juice
    (25, 25, '40', 1), -- Sidecar with Brandy
    (25, 15, '20', 1), -- Sidecar with Triple Sec
    (25, 7, '20', 2), -- Sidecar with Lemon Juice
    (26, 5, '50', 1), -- Mint Julep with Bourbon
    (26, 8, '5', 3), -- Mint Julep with Mint
    (26, 9, '10', 3), -- Mint Julep with Sugar
    (27, 26, '50', 1), -- Pisco Sour with Pisco
    (27, 6, '20', 2), -- Pisco Sour with Lime Juice
    (27, 9, '10', 3), -- Pisco Sour with Sugar
    (27, 27, '20', 2), -- Pisco Sour with Egg White
    (28, 25, '40', 1), -- Brandy Alexander with Brandy
    (28, 28, '60', 2), -- Brandy Alexander with Cream
    (28, 9, '10', 3), -- Brandy Alexander with Sugar
    (29, 2, '50', 1), -- Rum Punch with Rum
    (29, 12, '60', 2), -- Rum Punch with Pineapple Juice
    (29, 19, '10', 3), -- Rum Punch with Grenadine
    (30, 4, '40', 1), -- Gin Fizz with Gin
    (30, 7, '20', 2), -- Gin Fizz with Lemon Juice
    (30, 10, '100', 7); -- Gin Fizz with Ice Cubes

-- 15. Seed Data for `cocktail_steps`
INSERT INTO cocktail_steps (cocktail_id, step_number, instruction)
VALUES
    (1, 1, 'Muddle mint leaves with sugar syrup and lime juice in a glass.'),
    (1, 2, 'Add rum and fill the glass with ice cubes.'),
    (1, 3, 'Top with soda water and garnish with mint leaves.'),
    (2, 1, 'Place a sugar cube in a glass and add a few dashes of bitters.'),
    (2, 2, 'Add whiskey and a large ice cube.'),
    (2, 3, 'Stir gently and garnish with an orange peel.'),
    (3, 1, 'Rim the glass with salt and set aside.'),
    (3, 2, 'Shake tequila, lime juice, and triple sec with ice.'),
    (3, 3, 'Strain into the prepared glass and garnish with a lime wedge.'),
    (4, 1, 'Stir gin, sweet vermouth, and Campari with ice.'),
    (4, 2, 'Strain into a glass filled with ice.'),
    (4, 3, 'Garnish with an orange peel.'),
    (5, 1, 'Blend rum, coconut cream, and pineapple juice with ice until smooth.'),
    (5, 2, 'Pour into a chilled glass and garnish with a pineapple slice and cherry.'),
    (6, 1, 'Shake rum, lime juice, and sugar syrup with ice.'),
    (6, 2, 'Strain into a chilled glass and garnish with a lime wheel.'),
    (7, 1, 'Shake vodka, cranberry juice, lime juice, and triple sec with ice.'),
    (7, 2, 'Strain into a chilled glass and garnish with a lime twist.'),
    (8, 1, 'Shake whiskey, lemon juice, and sugar syrup with ice.'),
    (8, 2, 'Strain into a glass filled with ice.'),
    (8, 3, 'Garnish with a cherry and orange slice.'),
    (9, 1, 'Stir whiskey, sweet vermouth, and bitters with ice.'),
    (9, 2, 'Strain into a chilled glass and garnish with a cherry.'),
    (10, 1, 'Shake rum, lime juice, and orange liqueur with ice.'),
    (10, 2, 'Strain into a glass filled with ice.'),
    (10, 3, 'Garnish with a mint sprig and lime wedge.'),
    (11, 1, 'Pour gin into a glass filled with ice.'),
    (11, 2, 'Top with tonic water and stir gently.'),
    (11, 3, 'Garnish with a lime wedge.'),
    (12, 1, 'Pour tequila and orange juice into a glass filled with ice.'),
    (12, 2, 'Slowly pour grenadine over the back of a spoon.'),
    (12, 3, 'Garnish with a slice of orange and a cherry.'),
    (13, 1, 'Shake vodka, tomato juice, and spices with ice.'),
    (13, 2, 'Strain into a glass filled with ice.'),
    (13, 3, 'Garnish with a celery stick and lemon wedge.'),
    (14, 1, 'Pour vodka and orange juice into a glass filled with ice.'),
    (14, 2, 'Stir gently and garnish with an orange slice.'),
    (15, 1, 'Pour rum, cola, and lime juice into a glass filled with ice.'),
    (15, 2, 'Stir gently and garnish with a lime wedge.'),
    (16, 1, 'Shake vodka, rum, tequila, gin, and triple sec with ice.'),
    (16, 2, 'Strain into a glass filled with ice and top with cola.'),
    (17, 1, 'Shake gin, lemon juice, and sugar syrup with ice.'),
    (17, 2, 'Strain into a glass filled with ice and top with soda water.'),
    (18, 1, 'Shake gin, lemon juice, and sugar syrup with ice.'),
    (18, 2, 'Strain into a glass and top with champagne.'),
    (19, 1, 'Muddle lime wedges and sugar in a glass.'),
    (19, 2, 'Fill the glass with ice and add cachaça.'),
    (19, 3, 'Stir gently and garnish with a lime wedge.'),
    (20, 1, 'Stir gin and vermouth with ice.'),
    (20, 2, 'Strain into a chilled glass and garnish with an olive or lemon twist.');

-- 16. Seed Data for `comments`
INSERT INTO comments (user_id, cocktail_id, parent_comment_id, comment, created_at)
VALUES
    (1, 1, NULL, 'Refreshing drink! Perfect for summer.', '2023-06-25'),
    (2, 1, NULL, 'Love the mint flavor, a perfect balance.', '2023-06-26'),
    (3, 2, NULL, 'Old but gold, can never go wrong with an Old Fashioned.', '2023-07-20'),
    (4, 3, NULL, 'Margarita is always a party starter!', '2023-08-15'),
    (5, 4, NULL, 'Negroni is my favorite! Bittersweet goodness.', '2023-06-30'),
    (6, 5, NULL, 'Pina Colada for life! Love the tropical vibe.', '2023-07-05'),
    (7, 6, NULL, 'Simple but delicious Daiquiri, refreshing.', '2023-08-10'),
    (8, 7, NULL, 'Cosmopolitan is so classy and light, love it.', '2023-06-18'),
    (9, 8, NULL, 'Whiskey sour perfection!', '2023-08-25'),
    (10, 9, NULL, 'Manhattan is a classic, strong but smooth.', '2023-09-12');

-- 17. Seed Data for `likes`
INSERT INTO likes (user_id, cocktail_id, created_at)
VALUES
    (1, 1, '2023-06-26'),
    (2, 1, '2023-06-27'),
    (3, 2, '2023-07-21'),
    (4, 3, '2023-08-16'),
    (5, 4, '2023-07-01'),
    (6, 5, '2023-07-06'),
    (7, 6, '2023-08-11'),
    (8, 7, '2023-06-19'),
    (9, 8, '2023-08-26'),
    (10, 9, '2023-09-13');

-- 18. Seed Data for `follows`
INSERT INTO follows (user_id, followed_user_id, followed_at)
VALUES
    (1, 2, '2023-06-15'),
    (1, 3, '2023-06-16'),
    (2, 3, '2023-06-17'),
    (3, 4, '2023-06-18'),
    (4, 5, '2023-06-19'),
    (5, 6, '2023-06-20'),
    (6, 7, '2023-06-21'),
    (7, 8, '2023-06-22'),
    (8, 9, '2023-06-23'),
    (9, 10, '2023-06-24');


-- 19. Seed Data for `tags`
INSERT INTO tags (tag_id, name)
VALUES
    (1, 'Citrus'),
    (2, 'Tropical'),
    (3, 'Strong'),
    (4, 'Fruity'),
    (5, 'Refreshing'),
    (6, 'Creamy'),
    (7, 'Spicy'),
    (8, 'Classic'),
    (9, 'Herbal'),
    (10, 'Sweet');

-- 20. Seed Data for `cocktail_tags`
INSERT INTO cocktail_tags (cocktail_id, tag_id)
VALUES
    (1, 5),   -- Mojito -> Refreshing
    (2, 8),   -- Old Fashioned -> Classic
    (3, 4),   -- Margarita -> Fruity
    (4, 8),   -- Negroni -> Classic
    (5, 2),   -- Pina Colada -> Tropical
    (6, 5),   -- Daiquiri -> Refreshing
    (7, 4),   -- Cosmopolitan -> Fruity
    (8, 3),   -- Whiskey Sour -> Strong
    (9, 8),   -- Manhattan -> Classic
    (10, 2),  -- Mai Tai -> Tropical
    (11, 9),  -- Gin and Tonic -> Herbal
    (12, 4),  -- Tequila Sunrise -> Fruity
    (13, 7),  -- Bloody Mary -> Spicy
    (14, 4),  -- Screwdriver -> Fruity
    (15, 3),  -- Cuba Libre -> Strong
    (16, 3),  -- Long Island Iced Tea -> Strong
    (17, 5),  -- Tom Collins -> Refreshing
    (18, 10), -- French 75 -> Sweet
    (19, 5),  -- Caipirinha -> Refreshing
    (20, 9),  -- Martini -> Herbal
    (21, 4),  -- Paloma -> Fruity
    (22, 5),  -- Aperol Spritz -> Refreshing
    (23, 8),  -- Sazerac -> Classic
    (24, 5),  -- Moscow Mule -> Refreshing
    (25, 3),  -- Sidecar -> Strong
    (26, 9),  -- Mint Julep -> Herbal
    (27, 4),  -- Pisco Sour -> Fruity
    (28, 6),  -- Brandy Alexander -> Creamy
    (29, 2),  -- Rum Punch -> Tropical
    (30, 5);  -- Gin Fizz -> Refreshing