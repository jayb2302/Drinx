<?php

class SocialsService {
    private $socialsRepository;
    public function __construct(
        SocialsRepository $socialsRepository) {
        $this->socialsRepository = $socialsRepository;
    }

    // Fetch all social platforms
    public function getAllPlatforms() {
        return $this->socialsRepository->getAllPlatforms();
    }
    // Get all social links for a user
    public function getUserSocialLinks($userId): array
    {
        $socialLinks = $this->socialsRepository->getSocialLinksByUserId($userId);
    
        $linksArray = [];
        foreach ($socialLinks as $link) {
            $platform = $link->getPlatform();
            $platformId = $platform->getId();
            $linksArray[$platformId] = [
                'url' => $link->getUrl(),
                'platform_name' => $platform->getName(),
                'icon_class' => $platform->getIconClass(),
            ];
        }
        return $linksArray;
    }
    // Update an existing social link
    public function updateSocialLink($userId, $platformId, $url) {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException("Invalid URL format.");
        }
        return $this->socialsRepository->updateSocialLink($userId, $platformId, $url);
    }
    // Delete a social link
    public function deleteSocialLink($userId, $platformId) {
        return $this->socialsRepository->deleteSocialLink($userId, $platformId);
    }
    public function getSocialFormData($userId): array
    {
        // Fetch all available platforms
        $platforms = $this->socialsRepository->getAllPlatforms();
    
        // Fetch the user's social links (indexed by platform_id)
        $userSocialLinks = $this->getUserSocialLinks($userId);
    
        // Map platforms to include associated links
        $formData = [];
        foreach ($platforms as $platform) {
            $platformId = $platform->getId();
            $formData[] = [
                'platform_id' => $platformId,
                'platform_name' => $platform->getName(),
                'icon_class' => $platform->getIconClass(),
                'url' => $userSocialLinks[$platformId]['url'] ?? '', 
            ];
        }
        return $formData;
    }
}