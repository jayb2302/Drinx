DROP TRIGGER IF EXISTS `update_user_badges`;
DELIMITER $$
CREATE TRIGGER `update_user_badges` AFTER INSERT ON `cocktails` FOR EACH ROW BEGIN
    SET @recipe_count = (SELECT COUNT(*) FROM cocktails WHERE user_id = NEW.user_id);

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

    IF (SELECT COUNT(*)
        FROM user_badges
        WHERE user_id = NEW.user_id AND badge_id = @new_badge_id
    ) = 0 THEN
        UPDATE user_badges
        SET is_current = 0
        WHERE user_id = NEW.user_id AND is_current = 1;

        INSERT INTO user_badges (user_id, badge_id, earned_at, is_current)
        VALUES (NEW.user_id, @new_badge_id, NOW(), 1);
    ELSE
        UPDATE user_badges
        SET is_current = 1
        WHERE user_id = NEW.user_id AND badge_id = @new_badge_id;
    END IF;
END
$$
DELIMITER ;