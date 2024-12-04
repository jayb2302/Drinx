<?php
require_once __DIR__ . '/../config/dependencies.php';
class TagController
{
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function getAllTags()
    {
        $tags = $this->tagRepository->getAllTags();
        $this->jsonResponse(
            $tags ? 'success' : 'error',
            $tags ? 'Tags retrieved successfully.' : 'No tags found.',
            $tags ? ['tags' => $tags] : [],
            $tags ? 200 : 404
        );
    }

    public function getTagById($tagId)
    {
        $tag = $this->tagRepository->getTagById($tagId);
        $this->jsonResponse(
            $tag ? 'success' : 'error',
            $tag ? 'Tag retrieved successfully.' : 'Tag not found.',
            $tag ? ['tag' => $tag] : [],
            $tag ? 200 : 404
        );
    }

    // Save (add/update) a tag
    public function saveTag()
    {
        // Check if the user is an admin
        if (!AuthController::isAdmin()) {
            http_response_code(403);
            echo json_encode(['error' => 'You do not have permission to perform this action.']);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = json_decode(file_get_contents('php://input'), true);

            // Log the incoming CSRF token and session token for debugging
            error_log("Incoming CSRF Token: " . $data['csrf_token']);
            error_log("Session CSRF Token: " . $_SESSION['csrf_token']);

            $csrfToken = $data['csrf_token'] ?? '';
            $sessionToken = $_SESSION['csrf_token'] ?? '';

            // Validate CSRF token
            if (!$sessionToken || !hash_equals($sessionToken, $csrfToken)) {
                http_response_code(403);
                echo json_encode(['status' => 'error', 'message' => 'Invalid or missing CSRF token.']);
                return;
            }

            // Sanitize and validate tag data
            $tagId = isset($data['tag_id']) ? sanitize($data['tag_id']) : null;
            $tagName = sanitize($data['tag_name'] ?? ''); // Ensure tag_name is set before sanitizing
            $tagCategoryId = sanitize($data['tag_category_id'] ?? ''); // Ensure tag_category_id is set before sanitizing

            // Validate if name and category are provided
            if (empty($tagName) || empty($tagCategoryId)) {
                http_response_code(400);
                echo json_encode(['error' => 'Tag name and category are required.']);
                exit();
            }

            // Save the tag data using the repository method
            $result = $this->tagRepository->save($tagName, $tagCategoryId, $tagId);

            // Return success or failure response
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Tag saved successfully.']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to save tag.']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed.']);
        }
    }

    public function editTag($tagId)
    {
        $editTag = $this->tagRepository->getTagById($tagId);
        $tags = $this->groupTagsByCategory($this->tagRepository->getAllTags());
        $tagCategories = $this->tagRepository->getAllTagCategories();

        require_once __DIR__ . '/../views/admin/manage_tags.php';
    }

    // Delete a tag
    public function deleteTag()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $csrfToken = $data['csrf_token'] ?? '';
        $sessionToken = $_SESSION['csrf_token'] ?? '';

        if (!$sessionToken || !hash_equals($sessionToken, $csrfToken)) {
            $this->jsonResponse('error', 'Invalid or missing CSRF token.', [], 403);
        }

        $tagId = isset($data['tag_id']) ? sanitize($data['tag_id']) : null;

        if (!$tagId) {
            $this->jsonResponse('error', 'Invalid tag ID.', [], 400);
        }

        $result = $this->tagRepository->deleteTag($tagId);

        $this->jsonResponse(
            $result ? 'success' : 'error',
            $result ? 'Tag deleted successfully.' : 'Failed to delete the tag.',
            [],
            $result ? 200 : 500
        );
    }

    private function groupTagsByCategory($tags)
    {
        $grouped = [];
        foreach ($tags as $tag) {
            $category = $tag['category_name'] ?? 'Uncategorized';
            $grouped[$category][] = $tag;
        }
        return $grouped;
    }
    // Helper function to send a JSON response  
    private function jsonResponse($status, $message, $data = [], $httpCode = 200)
    {
        http_response_code($httpCode);
        echo json_encode(array_merge(['status' => $status, 'message' => $message], $data));
        exit();
    }
}
