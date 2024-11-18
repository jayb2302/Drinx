<?php
class TagController
{
    private $tagRepository;

    public function __construct($tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }
    
    public function getAllTags()
    {
        $tags = $this->tagRepository->getAllTags();
    
        header('Content-Type: application/json');
        if ($tags) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'tags' => $tags]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'No tags found.']);
        }
        exit();
    }

    public function getTagById($tagId)
    {
        $tag = $this->tagRepository->getTagById($tagId);

        header('Content-Type: application/json');
        if ($tag) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'tag' => $tag]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Tag not found.']);
        }
        exit();
    }

    public function showManageTagsPage()
    {
        $tags = $this->groupTagsByCategory($this->tagRepository->getAllTags());
        $tagCategories = $this->tagRepository->getAllTagCategories();
        include '/../views/admin/manage_tags.php';
    }

    // Save (add/update) a tag
    public function saveTag()
    {
        error_log("Entering saveTag method"); // Debugging entry point

        $data = json_decode(file_get_contents('php://input'), true);

        $tagId = isset($data['tag_id']) ? sanitize($data['tag_id']) : null;
        $tagName = sanitize($data['tag_name']);
        $tagCategoryId = sanitize($data['tag_category_id']);

        // Ensure fields are not empty
        if (empty($tagName) || empty($tagCategoryId)) {
            http_response_code(400); // Bad request for missing fields
            echo json_encode(['status' => 'error', 'message' => 'Tag name and category are required.']);
            exit();
        }

        // Save the tag (add or update)
        $result = $this->tagRepository->save($tagName, $tagCategoryId, $tagId);

        if ($result) {
            http_response_code(200); // Success
            echo json_encode(['status' => 'success', 'message' => 'Tag saved successfully.']);
        } else {
            http_response_code(500); // Internal server error
            echo json_encode(['status' => 'error', 'message' => 'Failed to save tag.']);
        }
        exit();
    }
    
    public function editTag($tagId)
    {
        $editTag = $this->tagRepository->getTagById($tagId);
        $tags = $this->groupTagsByCategory($this->tagRepository->getAllTags());
        $tagCategories = $this->tagRepository->getAllTagCategories();
        include 'views/admin/manage_tags.php';
    }

    // Delete a tag
    public function deleteTag()
    {
        // Get the data from the request
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Log the received data for debugging
        error_log('Received data: ' . print_r($data, true));
    
        // Retrieve tag_id
        $tagId = isset($data['tag_id']) ? $data['tag_id'] : null;
    
        // Check if tag_id is missing or invalid
        if (!$tagId) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid tag ID.']);
            exit();
        }
    
        // Proceed to delete the tag
        $result = $this->tagRepository->deleteTag($tagId);
    
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Tag deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete the tag.']);
        }
        exit();
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
}
