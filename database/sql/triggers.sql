-- This file contains SQL statements to create database triggers.
-- Triggers are used to automate actions, such as awarding badges when certain conditions are met.



------------------ User activity triggers

-- insert initial empty rows into user_activity table
INSERT INTO user_activity (user_id, rank_id, points, cocktails_uploaded, likes_received)
SELECT u.user_id, NULL, 0, 0, 0
FROM users u
ON DUPLICATE KEY UPDATE user_activity.user_id = u.user_id;


-- Update user_activity likes with already existing likes
UPDATE user_activity ua
JOIN (
    SELECT c.user_id, COUNT(l.like_id) AS total_likes
    FROM cocktails c
    LEFT JOIN likes l ON c.cocktail_id = l.cocktail_id
    GROUP BY c.user_id
) AS user_likes ON ua.user_id = user_likes.user_id
SET ua.likes_received = user_likes.total_likes;


-- Trigger for updating user_activity likes when a new like is added or removed
DELIMITER //

CREATE TRIGGER after_like_insert
AFTER INSERT ON likes
FOR EACH ROW
BEGIN
    UPDATE user_activity ua
    JOIN cocktails c ON ua.user_id = c.user_id
    SET ua.likes_received = ua.likes_received + 1
    WHERE c.cocktail_id = NEW.cocktail_id;
END;
//

CREATE TRIGGER after_like_delete
AFTER DELETE ON likes
FOR EACH ROW
BEGIN
    UPDATE user_activity ua
    JOIN cocktails c ON ua.user_id = c.user_id
    SET ua.likes_received = ua.likes_received - 1
    WHERE c.cocktail_id = OLD.cocktail_id;
END;
//

DELIMITER ;

