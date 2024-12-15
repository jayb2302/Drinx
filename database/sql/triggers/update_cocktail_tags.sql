DELIMITER //

CREATE TRIGGER update_cocktail_tags AFTER INSERT ON ingredient_tags
FOR EACH ROW
BEGIN
    -- Insert the new tag into cocktail_tags for all cocktails that contain the new ingredient
    INSERT INTO cocktail_tags (cocktail_id, tag_id, created_at)
    SELECT ci.cocktail_id, NEW.tag_id, NOW()
    FROM cocktail_ingredients ci
    WHERE ci.ingredient_id = NEW.ingredient_id
        -- Ensure the tag is not already associated with the cocktail
        AND NOT EXISTS (
            SELECT 1
            FROM cocktail_tags ct
            WHERE ct.cocktail_id = ci.cocktail_id
            AND ct.tag_id = NEW.tag_id
        );
END //

DELIMITER ;