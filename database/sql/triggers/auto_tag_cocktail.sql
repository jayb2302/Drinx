DELIMITER //
CREATE OR REPLACE TRIGGER auto_tag_cocktail
AFTER INSERT ON cocktail_ingredients
FOR EACH ROW
BEGIN
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
END //
DELIMITER ;
