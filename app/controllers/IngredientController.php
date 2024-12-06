<?php
require_once __DIR__ . 'BaseController.php';

class IngredientController
{
    private $ingredientService;
    private $tagService;

    public function __construct(
        IngredientService $ingredientService,
        TagService $tagService
    ) {
        $this->ingredientService = $ingredientService;
        $this->tagService = $tagService;
    }

    public function manageIngredients()
    {
        $ingredientsWithTags = $this->ingredientService->getIngredientsByTags();

        // Debugging: Output the data to ensure it's populated correctly
        // error_log(print_r($ingredientsWithTags, true)); // Logs to server logs
        echo '<pre>';
        print_r($ingredientsWithTags); // Displays data in the browser
        echo '</pre>';
        // die(); // Stops execution for debugging

        require_once __DIR__ . '/../views/ingredients/manage_ingredients.php';
    }
    public function getUncategorizedIngredients()
    {
        try {
            $uncategorized = $this->ingredientService->fetchUncategorizedIngredients();

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
            $csrfToken = $data['csrf_token'] ?? '';
            $sessionToken = $_SESSION['csrf_token'] ?? '';
        
            // Validate CSRF token
            if (!$sessionToken || !hash_equals($sessionToken, $csrfToken)) {
                http_response_code(403);
                echo json_encode(['status' => 'error', 'message' => 'Invalid or missing CSRF token.']);
                return;
            }
            $ingredientId = $data['ingredient_id'] ?? null;
            $ingredientName = trim($data['ingredient_name'] ?? '');
            $tagId = $data['tag_id'] ?? null;


            if (!$ingredientId || !$tagId) {
                http_response_code(400);
                error_log("Invalid ingredient or tag ID.");
                echo json_encode(['status' => 'error', 'message' => 'Invalid ingredient ID or tag ID provided.']);
                return;
            }

            // Ensure ingredient exists
            if (!$this->ingredientService->doesIngredientExist($ingredientId)) {
                http_response_code(404);
                error_log("Ingredient not found: $ingredientId");
                echo json_encode(['status' => 'error', 'message' => 'Ingredient not found.']);
                return;
            }

            // Ensure tag exists
            if (!$this->tagService->doesTagExist($tagId)) {
                http_response_code(404);
                error_log("Tag not found: $tagId");
                echo json_encode(['status' => 'error', 'message' => 'Tag not found.']);
                return;
            }

            // Assign the tag
            $result = $this->ingredientService->assignTag($ingredientId, $tagId);

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
            
            $csrfToken = $data['csrf_token'] ?? '';
            $sessionToken = $_SESSION['csrf_token'] ?? '';

            if (!$sessionToken || !hash_equals($sessionToken, $csrfToken)) {
                http_response_code(403);
                echo json_encode(['status' => 'error', 'message' => 'Invalid or missing CSRF token.']);
                return;
            }

            $ingredientName = trim($data['ingredient_name'] ?? '');

            // Check if ingredient name is empty
            if (empty($ingredientName)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Ingredient name is required.']);
                return;
            }

            // Check if ingredient already exists by name
            $ingredientId = $this->ingredientService->getIngredientIdByName($ingredientName);

            if ($ingredientId) {
                // If the ingredient already exists, send an error
                http_response_code(409);
                echo json_encode(['status' => 'error', 'message' => 'Ingredient already exists.']);
                return;
            }

            // Create the new ingredient
            $ingredientId = $this->ingredientService->createIngredient($ingredientName);

            if ($ingredientId) {
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

            $result = $this->ingredientService->updateIngredientName($ingredientId, $ingredientName);

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
            $result = $this->ingredientService->deleteIngredient($ingredientId);

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
