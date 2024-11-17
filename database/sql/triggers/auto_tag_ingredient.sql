DELIMITER //
DROP TRIGGER IF EXISTS auto_tag_ingredient;
//
CREATE TRIGGER auto_tag_ingredient
AFTER INSERT ON ingredients
FOR EACH ROW
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM ingredient_tags
        WHERE ingredient_id = NEW.ingredient_id
    ) THEN
        INSERT INTO ingredient_tags (ingredient_id, tag_id)
        SELECT NEW.ingredient_id, t.tag_id
        FROM tags t
        WHERE t.name = 'Uncategorized';
    END IF;
END;
//
DELIMITER ;