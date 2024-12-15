DELIMITER $$

CREATE TRIGGER remove_cocktail_tags AFTER DELETE ON cocktail_ingredients
FOR EACH ROW
BEGIN
    -- Check if the tag associated with the removed ingredient is still needed
    DELETE FROM cocktail_tags
    WHERE cocktail_id = OLD.cocktail_id
    AND tag_id IN (
        SELECT tag_id
        FROM ingredient_tags
        WHERE ingredient_id = OLD.ingredient_id
    )
    AND NOT EXISTS (
        SELECT 1
        FROM cocktail_ingredients ci
        JOIN ingredient_tags it ON ci.ingredient_id = it.ingredient_id
        WHERE ci.cocktail_id = OLD.cocktail_id
        AND it.tag_id = cocktail_tags.tag_id
    );
END$$

DELIMITER ;