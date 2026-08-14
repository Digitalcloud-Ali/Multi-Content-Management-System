<?php
/**
 * Modern Content Management Service — aligned with install.php schema
 * (posts.postid/author_id, categories.categoryid, users.userid).
 */

class ContentService {
    private $db;
    private $validator;
    private $categoriesTable = 'categories';
    
    public function __construct() {
        $this->db = getDB();
        $this->validator = getValidator();
        $this->categoriesTable = $this->resolveCategoriesTable();
    }

    /** Prefer core_categories after pack apply renames modern categories. */
    private function resolveCategoriesTable() {
        try {
            $conn = $this->db->getConnection();
            $r = $conn->query("SHOW TABLES LIKE 'core_categories'");
            if ($r && $r->num_rows > 0) {
                return 'core_categories';
            }
            $r2 = $conn->query("SHOW COLUMNS FROM categories LIKE 'categoryid'");
            if ($r2 && $r2->num_rows > 0) {
                return 'categories';
            }
        } catch (Exception $e) {
            // fall through
        }
        return 'core_categories';
    }
    
    public function getBlogPosts($page = 1, $limit = 10, $categoryId = null) {
        try {
            $offset = ($page - 1) * $limit;
            
            $whereClause = "WHERE p.status = 'published'";
            $params = [];
            $types = '';
            
            if ($categoryId) {
                $whereClause .= " AND p.category_id = ?";
                $params[] = $categoryId;
                $types .= 'i';
            }
            
            $params[] = $limit;
            $params[] = $offset;
            $types .= 'ii';
            
            $query = "SELECT p.*, p.postid AS id, c.name as category_name, u.username as author_name 
                     FROM posts p 
                     LEFT JOIN {$this->categoriesTable} c ON p.category_id = c.categoryid 
                     LEFT JOIN users u ON p.author_id = u.userid 
                     {$whereClause} 
                     ORDER BY p.created_at DESC 
                     LIMIT ? OFFSET ?";
            
            $posts = $this->db->queryAll($query, $types, $params);
            
            $countQuery = "SELECT COUNT(*) as total FROM posts p {$whereClause}";
            $countParams = array_slice($params, 0, -2);
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
    
    public function getBlogPost($id) {
        try {
            $query = "SELECT p.*, p.postid AS id, c.name as category_name, u.username as author_name 
                     FROM posts p 
                     LEFT JOIN {$this->categoriesTable} c ON p.category_id = c.categoryid 
                     LEFT JOIN users u ON p.author_id = u.userid 
                     WHERE p.postid = ? AND p.status = 'published'";
            
            $post = $this->db->queryOne($query, 'i', [$id]);
            
            if ($post) {
                $this->incrementViewCount($id);
            }
            
            return $post;
            
        } catch (Exception $e) {
            logError("Get blog post error: " . $e->getMessage(), 'ERROR');
            return null;
        }
    }

    /** Published CMS page by slug (flagship / pages table). */
    public function getPageBySlug($slug) {
        try {
            $slug = trim((string) $slug);
            if ($slug === '') {
                return null;
            }
            return $this->db->queryOne(
                "SELECT * FROM pages WHERE slug = ? AND status = 'published' LIMIT 1",
                's',
                [$slug]
            );
        } catch (Exception $e) {
            logError("Get page error: " . $e->getMessage(), 'ERROR');
            return null;
        }
    }
    
    public function createBlogPost($data, $userId) {
        try {
            $rules = [
                'title' => 'required|min:5|max:200',
                'content' => 'required|min:50',
                'category_id' => 'required|integer',
                'excerpt' => 'max:500'
            ];
            
            if (!$this->validator->validate($data, $rules)) {
                return ['success' => false, 'errors' => $this->validator->getErrors()];
            }
            
            $title = Validator::sanitize($data['title']);
            $content = Validator::sanitize($data['content'], 'html');
            $excerpt = Validator::sanitize($data['excerpt'] ?? '');
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
            $slug = trim($slug, '-');
            
            $category = $this->db->queryOne("SELECT categoryid FROM {$this->categoriesTable} WHERE categoryid = ?", 'i', [$data['category_id']]);
            if (!$category) {
                return ['success' => false, 'message' => 'Invalid category'];
            }
            
            $result = $this->db->execute(
                "INSERT INTO posts (title, slug, content, excerpt, category_id, author_id, status, created_at, updated_at) 
                 VALUES (?, ?, ?, ?, ?, ?, 'draft', NOW(), NOW())",
                'ssssii',
                [$title, $slug, $content, $excerpt, $data['category_id'], $userId]
            );
            
            if ($result['affected_rows'] > 0) {
                return ['success' => true, 'post_id' => $result['insert_id'], 'message' => 'Post created successfully'];
            }
            return ['success' => false, 'message' => 'Failed to create post'];
            
        } catch (Exception $e) {
            logError("Create blog post error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Failed to create post'];
        }
    }
    
    public function updateBlogPost($id, $data, $userId) {
        try {
            $rules = [
                'title' => 'required|min:5|max:200',
                'content' => 'required|min:50',
                'category_id' => 'required|integer',
                'excerpt' => 'max:500'
            ];
            
            if (!$this->validator->validate($data, $rules)) {
                return ['success' => false, 'errors' => $this->validator->getErrors()];
            }
            
            $post = $this->db->queryOne("SELECT author_id FROM posts WHERE postid = ?", 'i', [$id]);
            if (!$post) {
                return ['success' => false, 'message' => 'Post not found'];
            }
            
            $currentUser = $this->db->queryOne("SELECT role FROM users WHERE userid = ?", 'i', [$userId]);
            $isAdmin = $currentUser && in_array($currentUser['role'], ['admin', 'administrator'], true);
            if ((int) $post['author_id'] !== (int) $userId && !$isAdmin) {
                return ['success' => false, 'message' => 'Not authorized to edit this post'];
            }
            
            $title = Validator::sanitize($data['title']);
            $content = Validator::sanitize($data['content'], 'html');
            $excerpt = Validator::sanitize($data['excerpt'] ?? '');
            
            $result = $this->db->execute(
                "UPDATE posts SET title = ?, content = ?, excerpt = ?, category_id = ?, updated_at = NOW() WHERE postid = ?",
                'sssii',
                [$title, $content, $excerpt, $data['category_id'], $id]
            );
            
            if ($result['affected_rows'] > 0) {
                return ['success' => true, 'message' => 'Post updated successfully'];
            }
            return ['success' => false, 'message' => 'No changes made'];
            
        } catch (Exception $e) {
            logError("Update blog post error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Failed to update post'];
        }
    }
    
    public function deleteBlogPost($id, $userId) {
        try {
            $post = $this->db->queryOne("SELECT author_id FROM posts WHERE postid = ?", 'i', [$id]);
            if (!$post) {
                return ['success' => false, 'message' => 'Post not found'];
            }
            
            $currentUser = $this->db->queryOne("SELECT role FROM users WHERE userid = ?", 'i', [$userId]);
            $isAdmin = $currentUser && in_array($currentUser['role'], ['admin', 'administrator'], true);
            if ((int) $post['author_id'] !== (int) $userId && !$isAdmin) {
                return ['success' => false, 'message' => 'Not authorized to delete this post'];
            }
            
            $result = $this->db->execute(
                "UPDATE posts SET status = 'private', updated_at = NOW() WHERE postid = ?",
                'i',
                [$id]
            );
            
            if ($result['affected_rows'] > 0) {
                return ['success' => true, 'message' => 'Post deleted successfully'];
            }
            return ['success' => false, 'message' => 'Failed to delete post'];
            
        } catch (Exception $e) {
            logError("Delete blog post error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Failed to delete post'];
        }
    }
    
    public function getCategories() {
        try {
            return $this->db->queryAll("SELECT *, categoryid AS id FROM {$this->categoriesTable} ORDER BY name ASC");
        } catch (Exception $e) {
            logError("Get categories error: " . $e->getMessage(), 'ERROR');
            return [];
        }
    }
    
    public function createCategory($data) {
        try {
            $rules = [
                'name' => 'required|min:2|max:100',
                'description' => 'max:500'
            ];
            
            if (!$this->validator->validate($data, $rules)) {
                return ['success' => false, 'errors' => $this->validator->getErrors()];
            }
            
            $existing = $this->db->queryOne("SELECT categoryid FROM {$this->categoriesTable} WHERE name = ?", 's', [$data['name']]);
            if ($existing) {
                return ['success' => false, 'message' => 'Category name already exists'];
            }
            
            $name = Validator::sanitize($data['name']);
            $description = Validator::sanitize($data['description'] ?? '');
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
            $slug = trim($slug, '-');
            
            $result = $this->db->execute(
                "INSERT INTO {$this->categoriesTable} (name, slug, description) VALUES (?, ?, ?)",
                'sss',
                [$name, $slug, $description]
            );
            
            if ($result['affected_rows'] > 0) {
                return ['success' => true, 'category_id' => $result['insert_id'], 'message' => 'Category created successfully'];
            }
            return ['success' => false, 'message' => 'Failed to create category'];
            
        } catch (Exception $e) {
            logError("Create category error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Failed to create category'];
        }
    }
    
    public function searchPosts($query, $page = 1, $limit = 10) {
        try {
            $offset = ($page - 1) * $limit;
            $searchTerm = "%{$query}%";
            
            $sql = "SELECT p.*, p.postid AS id, c.name as category_name, u.username as author_name 
                    FROM posts p 
                    LEFT JOIN {$this->categoriesTable} c ON p.category_id = c.categoryid 
                    LEFT JOIN users u ON p.author_id = u.userid 
                    WHERE p.status = 'published' 
                    AND (p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?) 
                    ORDER BY p.created_at DESC 
                    LIMIT ? OFFSET ?";
            
            $posts = $this->db->queryAll($sql, 'sssii', [
                $searchTerm, $searchTerm, $searchTerm, $limit, $offset
            ]);
            
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
    
    private function incrementViewCount($postId) {
        try {
            $this->db->execute("UPDATE posts SET views = COALESCE(views, 0) + 1 WHERE postid = ?", 'i', [$postId]);
        } catch (Exception $e) {
            logError("Increment view count error: " . $e->getMessage(), 'ERROR');
        }
    }
    
    public function getPopularPosts($limit = 5) {
        try {
            return $this->db->queryAll(
                "SELECT p.*, p.postid AS id, c.name as category_name 
                 FROM posts p 
                 LEFT JOIN {$this->categoriesTable} c ON p.category_id = c.categoryid 
                 WHERE p.status = 'published' 
                 ORDER BY p.views DESC, p.created_at DESC 
                 LIMIT ?",
                'i',
                [$limit]
            );
        } catch (Exception $e) {
            logError("Get popular posts error: " . $e->getMessage(), 'ERROR');
            return [];
        }
    }
    
    public function getRecentPosts($limit = 5) {
        try {
            return $this->db->queryAll(
                "SELECT p.*, p.postid AS id, c.name as category_name 
                 FROM posts p 
                 LEFT JOIN {$this->categoriesTable} c ON p.category_id = c.categoryid 
                 WHERE p.status = 'published' 
                 ORDER BY p.created_at DESC 
                 LIMIT ?",
                'i',
                [$limit]
            );
        } catch (Exception $e) {
            logError("Get recent posts error: " . $e->getMessage(), 'ERROR');
            return [];
        }
    }
}
