DELIMITER //
DROP TRIGGER IF EXISTS remove_uncategorized_tag //
CREATE TRIGGER remove_uncategorized_tag
AFTER INSERT ON ingredient_tags
FOR EACH ROW
BEGIN
    DELETE FROM ingredient_tags
    WHERE ingredient_id = NEW.ingredient_id
      AND tag_id = (SELECT tag_id FROM tags WHERE name = 'Uncategorized')
      AND NOT EXISTS (
          SELECT 1
          FROM ingredient_tags
          WHERE ingredient_id = NEW.ingredient_id
            AND tag_id != (SELECT tag_id FROM tags WHERE name = 'Uncategorized')
      );
END //
DELIMITER ;

-- This trigger removes the “Uncategorized” tag from an ingredient once another tag has been assigned. 
-- It ensures that when an ingredient gets a tag other than “Uncategorized,” the default “Uncategorized” tag is removed.