<?php
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
        $data = json_decode(file_get_contents('php://input'), true);

        $tagId = isset($data['tag_id']) ? sanitize($data['tag_id']) : null;
        $tagName = sanitize($data['tag_name']);
        $tagCategoryId = sanitize($data['tag_category_id']);

        if (empty($tagName) || empty($tagCategoryId)) {
            $this->jsonResponse('error', 'Tag name and category are required.', [], 400);
        }

        $result = $this->tagRepository->save($tagName, $tagCategoryId, $tagId);

        $this->jsonResponse(
            $result ? 'success' : 'error',
            $result ? 'Tag saved successfully.' : 'Failed to save tag.',
            [],
            $result ? 200 : 500
        );
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
