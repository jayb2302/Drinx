<?php
class Comment {
    private $commentId;
    private $userId;
    private $username;
    private $cocktailId;
    private $parentCommentId;
    private $commentText;
    private $createdAt;
    private $profilePicture;
    public $replies = [];

    public function __construct($commentId, $userId, $username, $cocktailId, $parentCommentId, $commentText, $createdAt) {
        $this->commentId = $commentId;
        $this->userId = $userId;
        $this->username = $username;
        $this->cocktailId = $cocktailId;
        $this->parentCommentId = $parentCommentId;
        $this->commentText = $commentText;
        $this->createdAt = $createdAt;
        $this->profilePicture = 'user-default.svg';
        $this->replies = []; 
    }
    public function getProfilePicture() {
        return $this->profilePicture;
    }
    public function setProfilePicture($profilePicture) {
        $this->profilePicture = $profilePicture;
    }

    // Getters
    public function getUsername() {
        return $this->username; 
    }

    public function getCommentText() {
        return $this->commentText;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getCommentId() {
        return $this->commentId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getCocktailId() {
        return $this->cocktailId;
    }

    public function getParentCommentId() {
        return $this->parentCommentId;
    }
    
}