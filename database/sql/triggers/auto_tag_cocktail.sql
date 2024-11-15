
DELIMITER $$

CREATE TRIGGER `auto_tag_cocktail`
AFTER INSERT ON `cocktail_ingredients`
FOR EACH ROW
BEGIN
    DECLARE tag_id INT;

    -- Flavor: Citrus
    IF NEW.ingredient_id IN (59, 7, 6, 45, 13, 20) THEN
        SELECT tag_id INTO tag_id FROM tags WHERE name = 'Citrus' AND category = 'Flavor';
        IF tag_id IS NOT NULL THEN
            INSERT IGNORE INTO cocktail_tags (cocktail_id, tag_id) VALUES (NEW.cocktail_id, tag_id);
        END IF;
    END IF;

    -- Flavor: Creamy
    IF NEW.ingredient_id IN (28, 11) THEN
        SELECT tag_id INTO tag_id FROM tags WHERE name = 'Creamy' AND category = 'Flavor';
        IF tag_id IS NOT NULL THEN
            INSERT IGNORE INTO cocktail_tags (cocktail_id, tag_id) VALUES (NEW.cocktail_id, tag_id);
        END IF;
    END IF;

    -- Flavor: Fruity
    IF NEW.ingredient_id IN (52, 14, 12, 50, 49, 48) THEN
        SELECT tag_id INTO tag_id FROM tags WHERE name = 'Fruity' AND category = 'Flavor';
        IF tag_id IS NOT NULL THEN
            INSERT IGNORE INTO cocktail_tags (cocktail_id, tag_id) VALUES (NEW.cocktail_id, tag_id);
        END IF;
    END IF;

    -- Flavor: Herbal
    IF NEW.ingredient_id IN (8, 17, 22) THEN
        SELECT tag_id INTO tag_id FROM tags WHERE name = 'Herbal' AND category = 'Flavor';
        IF tag_id IS NOT NULL THEN
            INSERT IGNORE INTO cocktail_tags (cocktail_id, tag_id) VALUES (NEW.cocktail_id, tag_id);
        END IF;
    END IF;

    -- Flavor: Spicy
    IF NEW.ingredient_id IN (19, 24) THEN
        SELECT tag_id INTO tag_id FROM tags WHERE name = 'Spicy' AND category = 'Flavor';
        IF tag_id IS NOT NULL THEN
            INSERT IGNORE INTO cocktail_tags (cocktail_id, tag_id);
        END IF;
    END IF;

    -- Flavor: Strong
    IF NEW.ingredient_id IN (1, 2, 3, 25, 5, 4, 41, 26) THEN
        SELECT tag_id INTO tag_id FROM tags WHERE name = 'Strong' AND category = 'Flavor';
        IF tag_id IS NOT NULL THEN
            INSERT IGNORE INTO cocktail_tags (cocktail_id, tag_id);
        END IF;
    END IF;

    -- Flavor: Sweet
    IF NEW.ingredient_id IN (47, 9, 48, 15) THEN
        SELECT tag_id INTO tag_id FROM tags WHERE name = 'Sweet' AND category = 'Flavor';
        IF tag_id IS NOT NULL THEN
            INSERT IGNORE INTO cocktail_tags (cocktail_id, tag_id);
        END IF;
    END IF;

    -- Flavor: Bitter
    IF NEW.ingredient_id IN (18, 17, 22) THEN
        SELECT tag_id INTO tag_id FROM tags WHERE name = 'Bitter' AND category = 'Flavor';
        IF tag_id IS NOT NULL THEN
            INSERT IGNORE INTO cocktail_tags (cocktail_id, tag_id);
        END IF;
    END IF;

    -- Mood: Refreshing
    IF NEW.ingredient_id IN (8, 23, 7) THEN
        SELECT tag_id INTO tag_id FROM tags WHERE name = 'Refreshing' AND category = 'Mood';
        IF tag_id IS NOT NULL THEN
            INSERT IGNORE INTO cocktail_tags (cocktail_id, tag_id);
        END IF;
    END IF;

    -- Mood: Sophisticated
    IF NEW.ingredient_id IN (5, 4, 25, 17, 22) THEN
        SELECT tag_id INTO tag_id FROM tags WHERE name = 'Sophisticated' AND category = 'Mood';
        IF tag_id IS NOT NULL THEN
            INSERT IGNORE INTO cocktail_tags (cocktail_id, tag_id);
        END IF;
    END IF;

    -- Occasion: Celebration
    IF NEW.ingredient_id IN (53, 21) THEN
        SELECT tag_id INTO tag_id FROM tags WHERE name = 'Celebration' AND category = 'Occasion';
        IF tag_id IS NOT NULL THEN
            INSERT IGNORE INTO cocktail_tags (cocktail_id, tag_id);
        END IF;
    END IF;

    -- Season: SummerFruits
    IF NEW.ingredient_id IN (50, 49, 14, 12) THEN
        SELECT tag_id INTO tag_id FROM tags WHERE name = 'SummerFruits' AND category = 'Season';
        IF tag_id IS NOT NULL THEN
            INSERT IGNORE INTO cocktail_tags (cocktail_id, tag_id);
        END IF;
    END IF;

    -- Temperature: Iced
    IF NEW.ingredient_id IN (51, 10) THEN
        SELECT tag_id INTO tag_id FROM tags WHERE name = 'Iced' AND category = 'Temp';
        IF tag_id IS NOT NULL THEN
            INSERT IGNORE INTO cocktail_tags (cocktail_id, tag_id);
        END IF;
    END IF;

    -- Add more trigger conditions based on the provided tag categories and ingredients...

END$$

DELIMITER ;
