<?php
class SocialLink {
    private $id;
    private $userId;
    private $platform;
    private $url;
    private $createdAt;

    public function __construct($id, $userId, Platform $platform, $url, $createdAt) {
        $this->id = $id;
        $this->userId = $userId;
        $this->platform = $platform;
        $this->url = $url;
        $this->createdAt = $createdAt;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getPlatform() {
        return $this->platform;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }
}
