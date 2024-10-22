<?php
        class Cocktail
        {
            private $cocktail_id;
            private $title;
            private $description;
            private $image;
            private $category_id;
            private $user_id;
            private $difficulty_id;
            private $ingredients = [];
            private $steps = [];
            private $tags = [];

            public function __construct(
                $cocktail_id = null,
                $title = '',
                $description = '',
                $image = '',
                $category_id = null,
                $user_id = null,
                array $ingredients = [], // Initialize ingredients array
                array $steps = [],
                array $tags = []
            ) {
                $this->cocktail_id = $cocktail_id;
                $this->title = $title;
                $this->description = $description;
                $this->image = $image;
                $this->category_id = $category_id;
                $this->user_id = $user_id;
                $this->ingredients = $ingredients;
                $this->steps = $steps;
                $this->tags = $tags;
            }

            // Getter and setter for user_id
            public function getUserId()
            {
                return $this->user_id;
            }

            public function setUserId($user_id)
            {
                $this->user_id = $user_id;
            }

            // Other getters
            public function getCocktailId()
            {
                return $this->cocktail_id;
            }

            public function getTitle()
            {
                return $this->title;
            }

            public function getDescription()
            {
                return $this->description;
            }

            public function getImage()
            {
                return $this->image;
            }

            public function getCategoryId()
            {
                return $this->category_id;
            }

            public function getIngredients(): array
            {
                return $this->ingredients;
            }

            public function getSteps(): array
            {
                return $this->steps;
            }

            public function getTags(): array
            {
                return $this->tags;
            }

            // Utility methods
            public function addIngredient(Ingredient $ingredient)
            {
                $this->ingredients[] = $ingredient;
            }

            public function addStep(Step $step)
            {
                $this->steps[] = $step;
            }

            public function addTag($tag)
            {
                $this->tags[] = $tag;
            }

            public function getDifficultyId()
            {
                return $this->difficulty_id;
            }   
        }