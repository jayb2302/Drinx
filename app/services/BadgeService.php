<?php
require_once __DIR__ . '/../repositories/BadgeRepository.php';

class BadgeService {
    private $badgeRepository;

    public function __construct() {
        $dbConnection = Database::getConnection();
        $this->badgeRepository = new BadgeRepository($dbConnection);
    }

    public function getUserBadges($userId) {
        return $this->badgeRepository->findBadgesByUserId($userId);
    }
}