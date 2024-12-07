<?php
class Platform {
    private $id;
    private $name;
    private $iconClass;

    public function __construct($id, $name, $iconClass) {
        $this->id = $id;
        $this->name = $name;
        $this->iconClass = $iconClass;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getIconClass() {
        return $this->iconClass;
    }
}
