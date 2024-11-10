<?php
class User
{
    private $id;
    private $username;
    private $email;
    private $password;
    private $accountStatusId;
    private $accountStatusName;
    private $joinDate;
    private $lastLogin;
    private $isAdmin;
    private $firstName;
    private $lastName;
    private $profilePicture;
    private $bio;
    private $rank;

    private $followersCount;
    private $followingCount;

    // ID
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    // Username
    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    // Email
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    // Password
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    // Account Status ID
    public function getAccountStatusId()
    {
        return $this->accountStatusId;
    }

    public function setAccountStatusId($accountStatusId)
    {
        $this->accountStatusId = $accountStatusId;
    }

    public function setAccountStatusName($statusName)
    {
        $this->accountStatusName = $statusName;
    }

    public function getAccountStatusName()
    {
        return $this->accountStatusName;
    }
    public function isActive()
    {
        return $this->getAccountStatusId() === 1; // Assume 1 is for 'Active'
    }
    public function isSuspended()
    {
        return $this->getAccountStatusId() === 2; // Assume 2 is for 'Suspended'
    }
    public function isBanned()
    {
        return $this->getAccountStatusId() === 3; // Assume 3 is for 'Banned'
    }

    // Account Status functionality
    public function canAddCocktail($cocktailUserId)
    {
        return !$this->isSuspended() && ($this->getId() === $cocktailUserId || $this->isAdmin());
    }
    public function canEditCocktail($cocktailUserId)
    {
        return !$this->isSuspended() && ($this->getId() === $cocktailUserId || $this->isAdmin());
    }
    public function canComment()
    {
        return !$this->isSuspended();
    }

    // Join Date
    public function getJoinDate()
    {
        return $this->joinDate;
    }

    public function setJoinDate($joinDate)
    {
        $this->joinDate = $joinDate;
    }

    // Last Login
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
    }

    // Is Admin
    public function isAdmin()
    {
        return $this->isAdmin;
    }

    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    // First Name
    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    // Last Name
    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    // Profile Picture
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
    }

    // Bio
    public function getBio()
    {
        return $this->bio;
    }

    public function setBio($bio)
    {
        $this->bio = $bio;
    }

    public function getRank() {
        return $this->rank;  
    }

    // Following methods
    public function getFollowersCount()
    {
        return $this->followersCount;
    }

    public function setFollowersCount($count)
    {
        $this->followersCount = $count;
    }

    public function getFollowingCount()
    {
        return $this->followingCount;
    }

    public function setFollowingCount($count)
    {
        $this->followingCount = $count;
    }

}