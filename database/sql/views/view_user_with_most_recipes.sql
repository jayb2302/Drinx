CREATE VIEW view_user_with_most_recipes AS
SELECT 
    u.user_id,
    u.username,
    p.profile_picture,
    COUNT(c.cocktail_id) AS recipe_count
FROM users u
LEFT JOIN cocktails c ON u.user_id = c.user_id
LEFT JOIN user_profile p ON u.user_id = p.user_id
GROUP BY u.user_id, u.username, p.profile_picture
ORDER BY recipe_count DESC;