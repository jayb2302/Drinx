<?php
class Tag {
    private $tagId;
    private $name;
    private $tagCategoryId; 

    // Constructor to initialize the Tag object
    public function __construct($tagId, $name, $tagCategoryId = null)
    {
        $this->tagId = $tagId;
        $this->name = $name;
        $this->tagCategoryId = $tagCategoryId; 
    }

    // Getters and setters 
    public function getTagId()
    {
        return $this->tagId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTagCategoryId()
    {
        return $this->tagCategoryId;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setTagCategoryId($tagCategoryId)
    {
        $this->tagCategoryId = $tagCategoryId;
    }

    // Convert the Tag object to an associative array (for database insertion/update)
    public function toArray()
    {
        return [
            'tag_id' => $this->tagId,
            'name' => $this->name,
            'tag_category_id' => $this->tagCategoryId 
        ];
    }
}