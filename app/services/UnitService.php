<?php
class UnitService {
    private $unitRepository;

    public function __construct($unitRepository) {
        $this->unitRepository = $unitRepository;
    }

    public function getAllUnits() {
        return $this->unitRepository->getAllUnits();
    }
}