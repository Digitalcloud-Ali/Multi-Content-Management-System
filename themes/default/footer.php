        </div> <!-- End of main content container -->
    </main>
    
    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>About Us</h5>
                    <p class="text-muted">
                        <?php echo getSetting('site_description', 'A modern content management system built with security and performance in mind.'); ?>
                    </p>
                    <div class="social-links">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-muted text-decoration-none">Home</a></li>
                        <li><a href="index.php?page=blog" class="text-muted text-decoration-none">Blog</a></li>
                        <li><a href="index.php?page=about" class="text-muted text-decoration-none">About</a></li>
                        <li><a href="index.php?page=contact" class="text-muted text-decoration-none">Contact</a></li>
                        <li><a href="index.php?page=privacy" class="text-muted text-decoration-none">Privacy Policy</a></li>
                        <li><a href="index.php?page=terms" class="text-muted text-decoration-none">Terms of Service</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4">
                    <h5>Contact Info</h5>
                    <ul class="list-unstyled text-muted">
                        <li><i class="fas fa-map-marker-alt me-2"></i> <?php echo getSetting('site_address', '123 Main St, City, Country'); ?></li>
                        <li><i class="fas fa-phone me-2"></i> <?php echo getSetting('site_phone', '+1 234 567 8900'); ?></li>
                        <li><i class="fas fa-envelope me-2"></i> <?php echo getSetting('site_email', 'info@example.com'); ?></li>
                    </ul>
                    
                    <h6 class="mt-3">Newsletter</h6>
                    <form class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="Enter your email">
                        <button class="btn btn-primary" type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">
                        &copy; <?php echo date('Y'); ?> <?php echo getSetting('site_name', 'RayCMS'); ?>. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 text-muted">
                        Powered by <a href="#" class="text-light text-decoration-none">Multi-Content Management System</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (if needed for legacy compatibility) -->
    <script src="includes/js/jquery-3.7.1.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="includes/js/custom.js"></script>
    
    <!-- CSRF Token for AJAX requests -->
    <script>
        // Set up CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Initialize popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    </script>
    
    <!-- Page-specific JavaScript -->
    <?php if (isset($pageScripts)): ?>
        <?php foreach ($pageScripts as $script): ?>
            <script src="<?php echo htmlspecialchars($script); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>