<?php
require_once __DIR__ . '/../models/Comment.php';
class CommentRepository {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }
     // Fetch a comment by its ID
     public function getCommentById($commentId) {
        $sql = "SELECT * FROM comments WHERE comment_id = :comment_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':comment_id', $commentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    // Fetch top-level comments
    public function getTopLevelCommentsByCocktailId($cocktailId) {
        $sql = "
            SELECT c.*, u.username 
            FROM comments c
            JOIN users u ON c.user_id = u.user_id
            WHERE c.cocktail_id = :cocktail_id AND c.parent_comment_id IS NULL 
            ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Convert each result to a Comment model
        $comments = [];
        foreach ($results as $result) {
            $comments[] = new Comment(
                $result->comment_id,
                $result->user_id,
                $result->username, 
                $result->cocktail_id,
                $result->parent_comment_id,
                $result->comment,
                $result->created_at
            );
        }
        return $comments;
    }

    // Fetch replies for a comment
    public function getRepliesByCommentId($commentId) {
        $sql = "
            SELECT c.*, u.username 
            FROM comments c
            JOIN users u ON c.user_id = u.user_id
            WHERE c.parent_comment_id = :comment_id 
            ORDER BY c.created_at ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':comment_id', $commentId, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Convert each result to a Comment model
        $replies = [];
        foreach ($results as $result) {
            $replies[] = new Comment(
                $result->comment_id,
                $result->user_id,
                $result->username, 
                $result->cocktail_id,
                $result->parent_comment_id,
                $result->comment,
                $result->created_at
            );
        }
        return $replies;
    }

    // Add a new comment
    public function addComment($userId, $cocktailId, $commentText, $parentCommentId = null) {
        try {
            $sql = "INSERT INTO comments (user_id, cocktail_id, comment, parent_comment_id) 
                    VALUES (:user_id, :cocktail_id, :comment, :parent_comment_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':cocktail_id', $cocktailId);
            $stmt->bindParam(':comment', $commentText);
            $stmt->bindParam(':parent_comment_id', $parentCommentId);
            
            $result = $stmt->execute();
            error_log("Comment Inserted: " . json_encode($stmt->errorInfo())); // Log the error info if any
            return $result;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage()); // Log the exception
            return false;
        }
    }
     // Delete a comment by its ID
     public function deleteComment($commentId) {
        $sql = "DELETE FROM comments WHERE comment_id = :comment_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':comment_id', $commentId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}