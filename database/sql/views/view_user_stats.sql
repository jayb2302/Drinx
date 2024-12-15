CREATE VIEW view_user_stats AS
SELECT 
    ct.user_id,
    COUNT(DISTINCT ct.cocktail_id) AS cocktail_count,
    COUNT(DISTINCT l.like_id) AS likes_received,
    COUNT(DISTINCT c.comment_id) AS comments_received
FROM cocktails ct
LEFT JOIN likes l ON l.cocktail_id = ct.cocktail_id
LEFT JOIN comments c ON c.cocktail_id = ct.cocktail_id
GROUP BY ct.user_id;
