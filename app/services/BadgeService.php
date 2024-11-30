<?php
require_once __DIR__ . '/../config/dependencies.php';
require_once __DIR__ . '/../models/Badge.php';
require_once __DIR__ . '/../repositories/BadgeRepository.php';


class BadgeService
{
    private $badgeRepository;

    public function __construct($badgeRepository)
    {
        $this->badgeRepository = $badgeRepository;
    }

    // Get badges earned by a specific user
    public function getUserBadges($userId)
    {
        return $this->badgeRepository->findBadgesByUserId($userId);
    }

    // Get a specific badge by ID
    public function getBadgeById($badgeId)
    {
        return $this->badgeRepository->findBadgeById($badgeId);
    }

    // Get all badges (for admin or frontend display purposes)
    public function getAllBadges()
    {
        return $this->badgeRepository->findAllBadges();
    }
    // Check for new badges and notify user
    public function checkAndNotifyNewBadge($userId, $cocktailCount)
    {
        error_log("Checking for new badges for User ID: $userId with Cocktail Count: $cocktailCount");
    
        // Determine the badge for the current cocktail count
        $currentBadge = $this->badgeRepository->findBadgeByCocktailCount($cocktailCount);
    
        // Handle the special case for the first badge
        if ($cocktailCount === 1) {
            if ($currentBadge) {
                error_log("User ID: $userId earned their first badge: " . $currentBadge->getName());
                $this->sendBadgeNotification($userId, $currentBadge);
            } else {
                error_log("No badge found for User ID: $userId at Cocktail Count: 1.");
            }
            return; // No need to check further
        }
    
        // Determine the badge for the previous cocktail count
        $previousBadge = $this->badgeRepository->findBadgeByCocktailCount($cocktailCount - 1);
    
        if ($currentBadge) {
            // If the current badge differs from the previous badge, notify the user
            if (!$previousBadge || $currentBadge->getId() > $previousBadge->getId()) {
                error_log("User ID: $userId earned a new badge: " . $currentBadge->getName());
                $this->sendBadgeNotification($userId, $currentBadge);
            } else {
                error_log("User ID: $userId has already earned this badge or a higher badge: " . $currentBadge->getName());
            }
        } else {
            error_log("No badge found for User ID: $userId with Cocktail Count: $cocktailCount.");
        }
    }
    
    



    // Send notification to the user
    private function sendBadgeNotification($userId, $badge): void
    {
        error_log("Sending badge notification to User ID: $userId for Badge: " . $badge->getName());

        $_SESSION['badge_notification'] = [
            'message' => "Congratulations! You've earned the '{$badge->getName()}' badge!",
            'badge_name' => $badge->getName(),
            'badge_description' => $badge->getDescription(),
        ];

        error_log("Badge notification set in session: " . print_r($_SESSION['badge_notification'], true));
    }

}