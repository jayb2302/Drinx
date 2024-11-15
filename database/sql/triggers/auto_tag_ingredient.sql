DELIMITER //
CREATE TRIGGER auto_tag_ingredient
AFTER INSERT ON ingredients
FOR EACH ROW
BEGIN
    INSERT INTO ingredient_tags (ingredient_id, tag_id)
    SELECT NEW.ingredient_id, t.tag_id
    FROM tags t
    WHERE t.name = 'Uncategorized';
END //
DELIMITER ;