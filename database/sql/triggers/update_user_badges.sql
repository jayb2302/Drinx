DROP TRIGGER IF EXISTS `update_user_badges`;
DELIMITER $$
CREATE TRIGGER `update_user_badges` AFTER INSERT ON `cocktails` FOR EACH ROW BEGIN
    -- Calculate the user's total recipe count
    SET @recipe_count = (SELECT COUNT(*) FROM cocktails WHERE user_id = NEW.user_id);

    -- Find new badge_id based on the recipe count
    SET @new_badge_id = (
        CASE
            WHEN @recipe_count >= 100 THEN 10
            WHEN @recipe_count >= 70 THEN 9
            WHEN @recipe_count >= 50 THEN 8
            WHEN @recipe_count >= 40 THEN 7
            WHEN @recipe_count >= 30 THEN 6
            WHEN @recipe_count >= 20 THEN 5
            WHEN @recipe_count >= 15 THEN 4
            WHEN @recipe_count >= 10 THEN 3
            WHEN @recipe_count >= 5 THEN 2
            ELSE 1
        END
    );

    -- Check if the exact badge already exists (regardless of is_current)
    IF (SELECT COUNT(*)
        FROM user_badges
        WHERE user_id = NEW.user_id AND badge_id = @new_badge_id
    ) = 0 THEN
        -- Mark all previous badges for the user as not current
        UPDATE user_badges
        SET is_current = 0
        WHERE user_id = NEW.user_id AND is_current = 1;

        -- Insert the new badge as current
        INSERT INTO user_badges (user_id, badge_id, earned_at, is_current)
        VALUES (NEW.user_id, @new_badge_id, NOW(), 1);
    ELSE
        -- Update the existing badge to set it as current (if it exists but is not current)
        UPDATE user_badges
        SET is_current = 1
        WHERE user_id = NEW.user_id AND badge_id = @new_badge_id;
    END IF;
END
$$
DELIMITER ;