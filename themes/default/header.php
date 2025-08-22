<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getSetting('site_title', 'Multi-Content Management System'); ?></title>
    <meta name="description" content="<?php echo getSetting('site_description', 'A modern content management system'); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="themes/default/style.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/favicon.png">
    
    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="<?php echo generateCsrfToken(); ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-home"></i>
                <?php echo getSetting('site_name', 'RayCMS'); ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=blog">
                            <i class="fas fa-blog"></i> Blog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=about">
                            <i class="fas fa-info-circle"></i> About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=contact">
                            <i class="fas fa-envelope"></i> Contact
                        </a>
                    </li>
                </ul>
                
                <!-- Search Form -->
                <form class="d-flex me-3" action="index.php" method="GET">
                    <input type="hidden" name="page" value="search">
                    <input class="form-control me-2" type="search" name="q" placeholder="Search..." 
                           value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                
                <!-- User Menu -->
                <ul class="navbar-nav">
                    <?php if (isAuthenticated()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                               data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i>
                                <?php echo htmlspecialchars($currentUser['username'] ?? 'User'); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if (hasRole('administrator')): ?>
                                    <li>
                                        <a class="dropdown-item" href="administrator/">
                                            <i class="fas fa-cog"></i> Admin Panel
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                
                                <li>
                                    <a class="dropdown-item" href="index.php?page=profile">
                                        <i class="fas fa-user-edit"></i> Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="index.php?page=my-posts">
                                        <i class="fas fa-file-alt"></i> My Posts
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="index.php?page=logout">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=login">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=register">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php displayFlashMessages(); ?>
    
    <!-- Main Content Container -->
    <main class="main-content">
        <div class="container py-4">
            <!-- Page Header -->
            <?php if (isset($pageTitle)): ?>
                <div class="page-header mb-4">
                    <h1 class="display-4"><?php echo htmlspecialchars($pageTitle); ?></h1>
                    <?php if (isset($pageSubtitle)): ?>
                        <p class="lead text-muted"><?php echo htmlspecialchars($pageSubtitle); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>