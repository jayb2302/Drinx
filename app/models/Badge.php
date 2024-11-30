<?php
class Badge {
    private $id;
    private $name;
    private $description;
    private $badgeImage; // Add this property if badges have associated images


    public function __construct($id, $name, $description, $badgeImage = null) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->badgeImage = $badgeImage;

    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }
    public function getBadgeImage() {
        return $this->badgeImage;
    }
}
