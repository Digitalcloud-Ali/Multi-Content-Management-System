<?php
/**
 * Modern Home Page Template
 * Displays recent posts and welcome content
 */

// Set page title
$pageTitle = 'Welcome to ' . getSetting('site_name', 'RayCMS');
$pageSubtitle = getSetting('site_description', 'A modern content management system');

// Get recent posts for the homepage
try {
    $recentPosts = $contentService->getRecentPosts(6);
    $popularPosts = $contentService->getPopularPosts(4);
    $categories = $contentService->getCategories();
} catch (Exception $e) {
    logError("Home page content loading error: " . $e->getMessage(), 'ERROR');
    $recentPosts = [];
    $popularPosts = [];
    $categories = [];
}
?>

<!-- Hero Section -->
<div class="hero-section text-center py-5 mb-5">
    <div class="container">
        <h1 class="display-3 fw-bold text-primary mb-4">
            Welcome to <?php echo getSetting('site_name', 'RayCMS'); ?>
        </h1>
        <p class="lead mb-4">
            <?php echo getSetting('site_description', 'A modern content management system built with security and performance in mind.'); ?>
        </p>
        <div class="hero-buttons">
            <a href="<?php echo htmlspecialchars(mc_url('blog'), ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary btn-lg me-3">
                <i class="fas fa-blog"></i> Read Our Blog
            </a>
            <?php if (!isAuthenticated()): ?>
                <a href="<?php echo htmlspecialchars(mc_url('register'), ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-user-plus"></i> Get Started
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="features-section mb-5">
    <div class="container">
        <h2 class="text-center mb-5">Why Choose Our Platform?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <h4>Secure & Reliable</h4>
                    <p class="text-muted">Built with modern security practices and reliable infrastructure.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-rocket fa-3x text-success"></i>
                    </div>
                    <h4>Fast Performance</h4>
                    <p class="text-muted">Optimized for speed and performance with modern technologies.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-mobile-alt fa-3x text-info"></i>
                    </div>
                    <h4>Mobile Friendly</h4>
                    <p class="text-muted">Responsive design that works perfectly on all devices.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Posts Section -->
<?php if (!empty($recentPosts)): ?>
<div class="recent-posts-section mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Latest Articles</h2>
            <a href="<?php echo htmlspecialchars(mc_url('blog'), ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline-primary">
                View All Posts <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="row g-4">
            <?php foreach (array_slice($recentPosts, 0, 3) as $post): ?>
            <div class="col-md-4">
                <div class="card h-100 post-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="<?php echo htmlspecialchars(mc_post_url($post), ENT_QUOTES, 'UTF-8'); ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted">
                            <?php echo truncateText($post['excerpt'] ?? $post['content'], 120); ?>
                        </p>
                        <div class="post-meta text-muted small">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($post['author_name'] ?? 'Unknown'); ?>
                            <span class="mx-2">•</span>
                            <i class="fas fa-calendar"></i> <?php echo formatDate($post['created_at'], 'M j, Y'); ?>
                            <?php if (!empty($post['category_name'])): ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-folder"></i> <?php echo htmlspecialchars($post['category_name']); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="<?php echo htmlspecialchars(mc_post_url($post), ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-sm btn-outline-primary">
                            Read More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Popular Posts Section -->
<?php if (!empty($popularPosts)): ?>
<div class="popular-posts-section mb-5">
    <div class="container">
        <h2 class="mb-4">Popular Articles</h2>
        <div class="row g-4">
            <?php foreach ($popularPosts as $post): ?>
            <div class="col-md-6">
                <div class="card popular-post-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="<?php echo htmlspecialchars(mc_post_url($post), ENT_QUOTES, 'UTF-8'); ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted">
                            <?php echo truncateText($post['excerpt'] ?? $post['content'], 100); ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="post-meta text-muted small">
                                <i class="fas fa-eye"></i> <?php echo number_format($post['views'] ?? 0); ?> views
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar"></i> <?php echo formatDate($post['created_at'], 'M j, Y'); ?>
                            </div>
                            <a href="<?php echo htmlspecialchars(mc_post_url($post), ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-sm btn-primary">
                                Read More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Categories Section -->
<?php if (!empty($categories)): ?>
<div class="categories-section mb-5">
    <div class="container">
        <h2 class="text-center mb-4">Browse by Category</h2>
        <div class="row g-3">
            <?php foreach ($categories as $category): ?>
            <div class="col-md-3 col-sm-6">
                <a href="<?php echo htmlspecialchars(mc_url('blog', ['category' => (int) ($category['id'] ?? $category['categoryid'] ?? 0)]), ENT_QUOTES, 'UTF-8'); ?>"
                   class="category-card text-decoration-none">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-folder fa-2x text-primary mb-2"></i>
                            <h6 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h6>
                            <?php if (!empty($category['description'])): ?>
                                <p class="card-text small text-muted">
                                    <?php echo truncateText($category['description'], 60); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Call to Action Section -->
<div class="cta-section text-center py-5 bg-primary text-white">
    <div class="container">
        <h2 class="mb-4">Ready to Get Started?</h2>
        <p class="lead mb-4">
            Join our community and start creating amazing content today!
        </p>
        <div class="cta-buttons">
            <?php if (!isAuthenticated()): ?>
                <a href="<?php echo htmlspecialchars(mc_url('register'), ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-light btn-lg me-3">
                    <i class="fas fa-user-plus"></i> Create Account
                </a>
                <a href="<?php echo htmlspecialchars(mc_url('login'), ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </a>
            <?php else: ?>
                <a href="<?php echo htmlspecialchars(mc_url('blog'), ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-light btn-lg me-3">
                    <i class="fas fa-blog"></i> Start Writing
                </a>
                <a href="<?php echo htmlspecialchars(mc_url('profile'), ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-user"></i> View Profile
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    margin: 0 15px;
}

.hero-section h1 {
    color: white;
}

.feature-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.post-card {
    border: none;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.post-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.popular-post-card {
    border: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.popular-post-card:hover {
    transform: translateY(-2px);
}

.category-card {
    transition: transform 0.3s ease;
}

.category-card:hover {
    transform: translateY(-3px);
}

.category-card .card {
    border: 2px solid transparent;
    transition: border-color 0.3s ease;
}

.category-card:hover .card {
    border-color: #007bff;
}

.cta-section {
    border-radius: 15px;
    margin: 0 15px;
}

.post-meta i {
    width: 16px;
    text-align: center;
}

.card-title a {
    color: #333;
    transition: color 0.3s ease;
}

.card-title a:hover {
    color: #007bff;
}
</style>
