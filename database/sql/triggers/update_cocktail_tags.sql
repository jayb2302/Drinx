DELIMITER //
CREATE TRIGGER update_cocktail_tags
AFTER UPDATE ON ingredient_tags
FOR EACH ROW
BEGIN
    -- Update cocktail tags when an ingredient's tag is updated
    INSERT INTO cocktail_tags (cocktail_id, tag_id)
    SELECT ci.cocktail_id, NEW.tag_id
    FROM cocktail_ingredients ci
    WHERE ci.ingredient_id = NEW.ingredient_id
    ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP;
END //
DELIMITER ;