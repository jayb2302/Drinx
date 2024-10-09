<?php
class Cocktail {
    public $cocktail_id;
    public $title;
    public $description;
    public $image;
    public $category_id;
    public $created_at;
    public $updated_at;

    public function __construct($cocktail_id, $title, $description, $image, $category_id, $created_at, $updated_at) {
        $this->cocktail_id = $cocktail_id;
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->category_id = $category_id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
?>