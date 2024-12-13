CREATE VIEW view_cocktails_sorted_by_likes AS
SELECT 
    c.*, 
    COUNT(l.like_id) AS like_count
FROM cocktails c
LEFT JOIN likes l ON c.cocktail_id = l.cocktail_id
GROUP BY c.cocktail_id
ORDER BY like_count DESC;