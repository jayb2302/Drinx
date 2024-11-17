DELIMITER //
DROP PROCEDURE IF EXISTS AddIngredientWithDefaultTag;
//
CREATE PROCEDURE AddIngredientWithDefaultTag(IN ingredientName VARCHAR(255))
BEGIN
    DECLARE existingIngredientId INT;

    -- Check if the ingredient already exists
    SELECT ingredient_id INTO existingIngredientId
    FROM ingredients
    WHERE name = ingredientName;

    -- If it doesn't exist, insert it and assign the "Uncategorized" tag
    IF existingIngredientId IS NULL THEN
        INSERT INTO ingredients (name) VALUES (ingredientName);
        SET existingIngredientId = LAST_INSERT_ID();
        
        -- Insert the uncategorized tag into ingredient_tags
        INSERT INTO ingredient_tags (ingredient_id, tag_id)
        SELECT existingIngredientId, tag_id
        FROM tags
        WHERE name = 'Uncategorized';
    ELSE
        -- Ingredient already exists, just assign the "Uncategorized" tag if not already assigned
        INSERT INTO ingredient_tags (ingredient_id, tag_id)
        SELECT existingIngredientId, tag_id
        FROM tags
        WHERE name = 'Uncategorized'
        AND NOT EXISTS (
            SELECT 1
            FROM ingredient_tags it
            WHERE it.ingredient_id = existingIngredientId
            AND it.tag_id = (SELECT tag_id FROM tags WHERE name = 'Uncategorized')
        );
    END IF;
END //
DELIMITER ;

-- This procedure handles adding a new ingredient to the database. 
-- If the ingredient doesn’t already exist, it inserts the new ingredient and assigns the “Uncategorized” tag. 
-- If the ingredient already exists, it checks if the “Uncategorized” tag is already assigned and ensures that it is added only once.