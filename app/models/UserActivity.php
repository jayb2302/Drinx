<?php

class UserActivity
{
    private $userId;
    private $rankId;
    private $points;
    private $cocktailsUploaded;
    private $likesReceived;

    public function __construct($userId, $rankId = null, $points = 0, $cocktailsUploaded = 0, $likesReceived = 0)
    {
        $this->userId = $userId;
        $this->rankId = $rankId;
        $this->points = $points;
        $this->cocktailsUploaded = $cocktailsUploaded;
        $this->likesReceived = $likesReceived;
    }

    // Getters
    public function getUserId()
    {
        return $this->userId;
    }

    public function getRankId()
    {
        return $this->rankId;
    }

    public function getPoints()
    {
        return $this->points;
    }

    public function getCocktailsUploaded()
    {
        return $this->cocktailsUploaded;
    }

    public function getLikesReceived()
    {
        return $this->likesReceived;
    }

    // Setters
    public function setRankId($rankId)
    {
        $this->rankId = $rankId;
    }

    public function incrementPoints($amount = 1)
    {
        $this->points += $amount;
    }

    public function decrementPoints($amount = 1)
    {
        $this->points = max(0, $this->points - $amount); // Ensure points do not go below 0
    }

    public function incrementCocktailsUploaded($amount = 1)
    {
        $this->cocktailsUploaded += $amount;
    }

    public function decrementCocktailsUploaded($amount = 1)
    {
        $this->cocktailsUploaded = max(0, $this->cocktailsUploaded - $amount); // Ensure this doesn't go below 0
    }

    public function incrementLikesReceived($amount = 1)
    {
        $this->likesReceived += $amount;
    }

    public function decrementLikesReceived($amount = 1)
    {
        $this->likesReceived = max(0, $this->likesReceived - $amount); // Ensure this doesn't go below 0
    }
}