<?php
class IngredientController
{
    private $ingredientRepository;
    private $tagRepository;

    public function __construct($ingredientRepository, $tagRepository)
    {
        $this->ingredientRepository = $ingredientRepository;
        $this->tagRepository = $tagRepository;
    }

    public function getUncategorizedIngredients()
    {
        try {
            $uncategorized = $this->ingredientRepository->fetchUncategorizedIngredients();

            if (!empty($uncategorized)) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'ingredients' => $uncategorized]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'ingredients' => []]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred.']);
        }
    }

    // Assign a tag to an ingredient
    public function assignTag()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $ingredientId = $data['ingredient_id'] ?? null;
            $ingredientName = trim($data['ingredient_name'] ?? '');
            $tagId = $data['tag_id'] ?? null;

            // Debugging log
            error_log("Assigning Tag - Ingredient ID: $ingredientId, Tag ID: $tagId");

            if (!$ingredientId || !$tagId) {
                http_response_code(400);
                error_log("Invalid ingredient or tag ID.");
                echo json_encode(['status' => 'error', 'message' => 'Invalid ingredient ID or tag ID provided.']);
                return;
            }

            // Ensure ingredient exists
            if (!$this->ingredientRepository->doesIngredientExist($ingredientId)) {
                http_response_code(404);
                error_log("Ingredient not found: $ingredientId");
                echo json_encode(['status' => 'error', 'message' => 'Ingredient not found.']);
                return;
            }

            // Ensure tag exists
            if (!$this->tagRepository->doesTagExist($tagId)) {
                http_response_code(404);
                error_log("Tag not found: $tagId");
                echo json_encode(['status' => 'error', 'message' => 'Tag not found.']);
                return;
            }

            // Assign the tag
            $result = $this->ingredientRepository->assignTag($ingredientId, $tagId);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Tag assigned successfully.']);
            } else {
                http_response_code(500);
                error_log("Failed to assign tag for Ingredient ID: $ingredientId");
                echo json_encode(['status' => 'error', 'message' => 'Failed to assign tag.']);
            }
        } catch (Exception $e) {
            error_log("Error in assignTag method: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred.']);
        }
    }

    public function createIngredient()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $ingredientName = trim($data['ingredient_name'] ?? '');

            // Check if ingredient name is empty
            if (empty($ingredientName)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Ingredient name is required.']);
                return;
            }

            // Check if ingredient already exists by name
            $ingredientId = $this->ingredientRepository->getIngredientIdByName($ingredientName);

            if ($ingredientId) {
                // If the ingredient already exists, send an error
                http_response_code(409);
                echo json_encode(['status' => 'error', 'message' => 'Ingredient already exists.']);
                return;
            }

            // Create the new ingredient
            $ingredientId = $this->ingredientRepository->createIngredient($ingredientName);

            if ($ingredientId) {
                // Get the "Uncategorized" tag ID using the repository
                $uncategorizedTagId = $this->ingredientRepository->getUncategorizedTagId();

                // Assign the "Uncategorized" tag
                $this->ingredientRepository->assignTag($ingredientId, $uncategorizedTagId);

                // Return success response
                echo json_encode(['status' => 'success', 'message' => 'Ingredient added successfully.', 'ingredient_id' => $ingredientId]);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to add ingredient.']);
            }
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage()); // Log the exception
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred.']);
        }
    }

    public function editIngredientName()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $ingredientId = $data['ingredient_id'] ?? null;
            $ingredientName = trim($data['ingredient_name'] ?? '');

            if (!$ingredientId || !$ingredientName) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid ingredient ID or name provided.']);
                return;
            }

            $result = $this->ingredientRepository->updateIngredientName($ingredientId, $ingredientName);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Ingredient name updated successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update ingredient name.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred.']);
        }
    }
    public function deleteIngredient()
    {
        try {
            // Get the data from the request body
            $data = json_decode(file_get_contents('php://input'), true);
            $ingredientId = $data['ingredient_id'] ?? null;

            // Validate the input
            if (!$ingredientId) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid ingredient ID provided.']);
                return;
            }

            // Call the repository to delete the ingredient
            $result = $this->ingredientRepository->deleteIngredient($ingredientId);

            // Return the response based on the result
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Ingredient deleted successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete ingredient.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred.']);
        }
    }
}
