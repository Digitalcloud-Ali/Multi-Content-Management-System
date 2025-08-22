<?php
/**
 * Modern Content Management Service
 * Handles blog posts, categories, and other content
 */

class ContentService {
    private $db;
    private $validator;
    
    public function __construct() {
        $this->db = getDB();
        $this->validator = getValidator();
    }
    
    /**
     * Get all blog posts with pagination
     */
    public function getBlogPosts($page = 1, $limit = 10, $categoryId = null) {
        try {
            $offset = ($page - 1) * $limit;
            
            $whereClause = "WHERE status = 'published'";
            $params = [];
            $types = '';
            
            if ($categoryId) {
                $whereClause .= " AND category_id = ?";
                $params[] = $categoryId;
                $types .= 'i';
            }
            
            $params[] = $limit;
            $params[] = $offset;
            $types .= 'ii';
            
            $query = "SELECT p.*, c.name as category_name, u.username as author_name 
                     FROM posts p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     LEFT JOIN members u ON p.user_id = u.id 
                     {$whereClause} 
                     ORDER BY p.created_at DESC 
                     LIMIT ? OFFSET ?";
            
            $posts = $this->db->queryAll($query, $types, $params);
            
            // Get total count for pagination
            $countQuery = "SELECT COUNT(*) as total FROM posts {$whereClause}";
            $countParams = array_slice($params, 0, -2); // Remove limit and offset
            $countTypes = substr($types, 0, -2);
            
            $totalResult = $this->db->queryOne($countQuery, $countTypes, $countParams);
            $total = $totalResult['total'] ?? 0;
            
            return [
                'posts' => $posts,
                'total' => $total,
                'pages' => ceil($total / $limit),
                'current_page' => $page
            ];
            
        } catch (Exception $e) {
            logError("Get blog posts error: " . $e->getMessage(), 'ERROR');
            return ['posts' => [], 'total' => 0, 'pages' => 0, 'current_page' => 1];
        }
    }
    
    /**
     * Get single blog post by ID
     */
    public function getBlogPost($id) {
        try {
            $query = "SELECT p.*, c.name as category_name, u.username as author_name 
                     FROM posts p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     LEFT JOIN members u ON p.user_id = u.id 
                     WHERE p.id = ? AND p.status = 'published'";
            
            $post = $this->db->queryOne($query, 'i', [$id]);
            
            if ($post) {
                // Increment view count
                $this->incrementViewCount($id);
            }
            
            return $post;
            
        } catch (Exception $e) {
            logError("Get blog post error: " . $e->getMessage(), 'ERROR');
            return null;
        }
    }
    
    /**
     * Create new blog post
     */
    public function createBlogPost($data, $userId) {
        try {
            // Validate input
            $rules = [
                'title' => 'required|min:5|max:200',
                'content' => 'required|min:50',
                'category_id' => 'required|integer',
                'excerpt' => 'max:500'
            ];
            
            if (!$this->validator->validate($data, $rules)) {
                return ['success' => false, 'errors' => $this->validator->getErrors()];
            }
            
            // Sanitize input
            $title = Validator::sanitize($data['title']);
            $content = Validator::sanitize($data['content'], 'html');
            $excerpt = Validator::sanitize($data['excerpt'] ?? '');
            
            // Check if category exists
            $category = $this->db->queryOne("SELECT id FROM categories WHERE id = ?", 'i', [$data['category_id']]);
            if (!$category) {
                return ['success' => false, 'message' => 'Invalid category'];
            }
            
            // Insert post
            $query = "INSERT INTO posts (title, content, excerpt, category_id, user_id, status, created_at, updated_at) 
                     VALUES (?, ?, ?, ?, ?, 'draft', NOW(), NOW())";
            
            $result = $this->db->execute($query, 'sssii', [
                $title,
                $content,
                $excerpt,
                $data['category_id'],
                $userId
            ]);
            
            if ($result['affected_rows'] > 0) {
                logError("New blog post created by user ID: {$userId}", 'INFO');
                return ['success' => true, 'post_id' => $result['insert_id'], 'message' => 'Post created successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to create post'];
            }
            
        } catch (Exception $e) {
            logError("Create blog post error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Failed to create post'];
        }
    }
    
    /**
     * Update blog post
     */
    public function updateBlogPost($id, $data, $userId) {
        try {
            // Validate input
            $rules = [
                'title' => 'required|min:5|max:200',
                'content' => 'required|min:50',
                'category_id' => 'required|integer',
                'excerpt' => 'max:500'
            ];
            
            if (!$this->validator->validate($data, $rules)) {
                return ['success' => false, 'errors' => $this->validator->getErrors()];
            }
            
            // Check if user owns the post or is admin
            $post = $this->db->queryOne("SELECT user_id FROM posts WHERE id = ?", 'i', [$id]);
            if (!$post) {
                return ['success' => false, 'message' => 'Post not found'];
            }
            
            $currentUser = getDB()->queryOne("SELECT usergroup FROM members WHERE id = ?", 'i', [$userId]);
            if ($post['user_id'] != $userId && $currentUser['usergroup'] !== 'administrator') {
                return ['success' => false, 'message' => 'Not authorized to edit this post'];
            }
            
            // Sanitize input
            $title = Validator::sanitize($data['title']);
            $content = Validator::sanitize($data['content'], 'html');
            $excerpt = Validator::sanitize($data['excerpt'] ?? '');
            
            // Update post
            $query = "UPDATE posts SET title = ?, content = ?, excerpt = ?, category_id = ?, updated_at = NOW() WHERE id = ?";
            
            $result = $this->db->execute($query, 'sssii', [
                $title,
                $content,
                $excerpt,
                $data['category_id'],
                $id
            ]);
            
            if ($result['affected_rows'] > 0) {
                logError("Blog post updated by user ID: {$userId}", 'INFO');
                return ['success' => true, 'message' => 'Post updated successfully'];
            } else {
                return ['success' => false, 'message' => 'No changes made'];
            }
            
        } catch (Exception $e) {
            logError("Update blog post error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Failed to update post'];
        }
    }
    
    /**
     * Delete blog post
     */
    public function deleteBlogPost($id, $userId) {
        try {
            // Check if user owns the post or is admin
            $post = $this->db->queryOne("SELECT user_id FROM posts WHERE id = ?", 'i', [$id]);
            if (!$post) {
                return ['success' => false, 'message' => 'Post not found'];
            }
            
            $currentUser = getDB()->queryOne("SELECT usergroup FROM members WHERE id = ?", 'i', [$userId]);
            if ($post['user_id'] != $userId && $currentUser['usergroup'] !== 'administrator') {
                return ['success' => false, 'message' => 'Not authorized to delete this post'];
            }
            
            // Soft delete (mark as deleted)
            $query = "UPDATE posts SET status = 'deleted', updated_at = NOW() WHERE id = ?";
            $result = $this->db->execute($query, 'i', [$id]);
            
            if ($result['affected_rows'] > 0) {
                logError("Blog post deleted by user ID: {$userId}", 'INFO');
                return ['success' => true, 'message' => 'Post deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to delete post'];
            }
            
        } catch (Exception $e) {
            logError("Delete blog post error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Failed to delete post'];
        }
    }
    
    /**
     * Get all categories
     */
    public function getCategories() {
        try {
            $query = "SELECT * FROM categories WHERE status = 'active' ORDER BY name ASC";
            return $this->db->queryAll($query);
        } catch (Exception $e) {
            logError("Get categories error: " . $e->getMessage(), 'ERROR');
            return [];
        }
    }
    
    /**
     * Create new category
     */
    public function createCategory($data) {
        try {
            // Validate input
            $rules = [
                'name' => 'required|min:2|max:100',
                'description' => 'max:500'
            ];
            
            if (!$this->validator->validate($data, $rules)) {
                return ['success' => false, 'errors' => $this->validator->getErrors()];
            }
            
            // Check if category name already exists
            $existing = $this->db->queryOne("SELECT id FROM categories WHERE name = ?", 's', [$data['name']]);
            if ($existing) {
                return ['success' => false, 'message' => 'Category name already exists'];
            }
            
            // Sanitize input
            $name = Validator::sanitize($data['name']);
            $description = Validator::sanitize($data['description'] ?? '');
            
            // Insert category
            $query = "INSERT INTO categories (name, description, status, created_at) VALUES (?, ?, 'active', NOW())";
            $result = $this->db->execute($query, 'ss', [$name, $description]);
            
            if ($result['affected_rows'] > 0) {
                logError("New category created: {$name}", 'INFO');
                return ['success' => true, 'category_id' => $result['insert_id'], 'message' => 'Category created successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to create category'];
            }
            
        } catch (Exception $e) {
            logError("Create category error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Failed to create category'];
        }
    }
    
    /**
     * Search posts
     */
    public function searchPosts($query, $page = 1, $limit = 10) {
        try {
            $offset = ($page - 1) * $limit;
            
            $searchTerm = "%{$query}%";
            
            $sql = "SELECT p.*, c.name as category_name, u.username as author_name 
                    FROM posts p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    LEFT JOIN members u ON p.user_id = u.id 
                    WHERE p.status = 'published' 
                    AND (p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?) 
                    ORDER BY p.created_at DESC 
                    LIMIT ? OFFSET ?";
            
            $posts = $this->db->queryAll($sql, 'sssii', [
                $searchTerm, $searchTerm, $searchTerm, $limit, $offset
            ]);
            
            // Get total count
            $countSql = "SELECT COUNT(*) as total FROM posts 
                        WHERE status = 'published' 
                        AND (title LIKE ? OR content LIKE ? OR excerpt LIKE ?)";
            
            $totalResult = $this->db->queryOne($countSql, 'sss', [$searchTerm, $searchTerm, $searchTerm]);
            $total = $totalResult['total'] ?? 0;
            
            return [
                'posts' => $posts,
                'total' => $total,
                'pages' => ceil($total / $limit),
                'current_page' => $page,
                'search_query' => $query
            ];
            
        } catch (Exception $e) {
            logError("Search posts error: " . $e->getMessage(), 'ERROR');
            return ['posts' => [], 'total' => 0, 'pages' => 0, 'current_page' => 1, 'search_query' => $query];
        }
    }
    
    /**
     * Increment view count for a post
     */
    private function incrementViewCount($postId) {
        try {
            $query = "UPDATE posts SET views = COALESCE(views, 0) + 1 WHERE id = ?";
            $this->db->execute($query, 'i', [$postId]);
        } catch (Exception $e) {
            logError("Increment view count error: " . $e->getMessage(), 'ERROR');
        }
    }
    
    /**
     * Get popular posts (by views)
     */
    public function getPopularPosts($limit = 5) {
        try {
            $query = "SELECT p.*, c.name as category_name 
                     FROM posts p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     WHERE p.status = 'published' 
                     ORDER BY p.views DESC, p.created_at DESC 
                     LIMIT ?";
            
            return $this->db->queryAll($query, 'i', [$limit]);
        } catch (Exception $e) {
            logError("Get popular posts error: " . $e->getMessage(), 'ERROR');
            return [];
        }
    }
    
    /**
     * Get recent posts
     */
    public function getRecentPosts($limit = 5) {
        try {
            $query = "SELECT p.*, c.name as category_name 
                     FROM posts p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     WHERE p.status = 'published' 
                     ORDER BY p.created_at DESC 
                     LIMIT ?";
            
            return $this->db->queryAll($query, 'i', [$limit]);
        } catch (Exception $e) {
            logError("Get recent posts error: " . $e->getMessage(), 'ERROR');
            return [];
        }
    }
}
?>
