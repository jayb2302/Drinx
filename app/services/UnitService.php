<?php
class UnitService {
    private $unitRepository;

    public function __construct(
        UnitRepository $unitRepository
    ) {
        $this->unitRepository = $unitRepository;
    }

    public function getAllUnits() {
        return $this->unitRepository->getAllUnits();
    }
}