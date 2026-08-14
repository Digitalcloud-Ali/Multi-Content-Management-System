<?php
/**
 * Front-controller routing helpers (pretty URLs via root .htaccess → mc_route).
 */

if (!function_exists('mc_base_path')) {
    function mc_base_path() {
        if (defined('SITE_BASE_PATH')) {
            return (string) SITE_BASE_PATH;
        }
        $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
        $dir = dirname($script);
        if ($dir === '/' || $dir === '\\' || $dir === '.') {
            return '';
        }
        return rtrim($dir, '/');
    }
}

if (!function_exists('mc_url')) {
    /**
     * Build a public URL. Prefers clean paths (/blog/my-post); works with root .htaccess.
     * @param string $path e.g. '', 'blog', 'about', 'post/my-slug'
     * @param array $query extra query args
     */
    function mc_url($path = '', array $query = []) {
        $base = mc_base_path();
        $path = trim((string) $path, '/');
        if ($path === '') {
            $url = ($base === '' ? '/' : $base . '/');
        } else {
            $url = ($base === '' ? '' : $base) . '/' . $path;
        }
        if ($query) {
            $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($query);
        }
        return $url;
    }
}

if (!function_exists('mc_post_url')) {
    function mc_post_url(array $post) {
        $slug = trim((string) ($post['slug'] ?? ''));
        if ($slug !== '') {
            return mc_url('post/' . rawurlencode($slug));
        }
        $id = (int) ($post['id'] ?? $post['postid'] ?? 0);
        return $id > 0 ? mc_url('post/' . $id) : mc_url('blog');
    }
}

if (!function_exists('mc_resolve_request')) {
    /**
     * Resolve front request into view context.
     * @return array{page:string,post:?array,cms_page:?array,route:string}
     */
    function mc_resolve_request(ContentService $contentService) {
        $route = isset($_GET['mc_route']) ? trim((string) $_GET['mc_route'], '/') : '';
        $legacy = isset($_GET['page']) ? trim((string) $_GET['page']) : '';

        $core = ['home', 'blog', 'about', 'contact', 'login', 'register', 'profile', 'logout', 'search'];
        $out = ['page' => 'home', 'post' => null, 'cms_page' => null, 'route' => $route];

        if ($route === '' && $legacy !== '') {
            $legacy = Validator::sanitize($legacy);
            if (in_array($legacy, $core, true)) {
                $out['page'] = $legacy;
                return $out;
            }
            if (is_numeric($legacy)) {
                $post = $contentService->getBlogPost((int) $legacy);
                if ($post) {
                    $out['page'] = 'single';
                    $out['post'] = $post;
                    return $out;
                }
            }
            $out['page'] = '404';
            return $out;
        }

        if ($route === '' || $route === 'index.php') {
            $out['page'] = 'home';
            return $out;
        }

        $parts = explode('/', $route);
        $head = strtolower($parts[0]);

        if (count($parts) === 1 && in_array($head, $core, true)) {
            $out['page'] = $head;
            return $out;
        }

        if ($head === 'post' && isset($parts[1]) && $parts[1] !== '') {
            $key = rawurldecode($parts[1]);
            if (is_numeric($key)) {
                $post = $contentService->getBlogPost((int) $key);
            } else {
                $post = $contentService->getBlogPostBySlug($key);
            }
            if ($post) {
                $out['page'] = 'single';
                $out['post'] = $post;
                return $out;
            }
            $out['page'] = '404';
            return $out;
        }

        if (($head === 'blog' || $head === 'page') && isset($parts[1]) && $parts[1] !== '') {
            $key = rawurldecode($parts[1]);
            if ($head === 'blog' || $head === 'post') {
                $post = is_numeric($key)
                    ? $contentService->getBlogPost((int) $key)
                    : $contentService->getBlogPostBySlug($key);
                if ($post) {
                    $out['page'] = 'single';
                    $out['post'] = $post;
                    return $out;
                }
            }
            if ($head === 'page') {
                $cms = $contentService->getPageBySlug($key);
                if ($cms) {
                    $out['page'] = 'cms_page';
                    $out['cms_page'] = $cms;
                    return $out;
                }
            }
        }

        // Bare slug: post first, then CMS page
        $slug = rawurldecode($route);
        if (strpos($slug, '/') === false) {
            $post = $contentService->getBlogPostBySlug($slug);
            if ($post) {
                $out['page'] = 'single';
                $out['post'] = $post;
                return $out;
            }
            $cms = $contentService->getPageBySlug($slug);
            if ($cms) {
                $out['page'] = 'cms_page';
                $out['cms_page'] = $cms;
                return $out;
            }
            if (is_numeric($slug)) {
                $post = $contentService->getBlogPost((int) $slug);
                if ($post) {
                    $out['page'] = 'single';
                    $out['post'] = $post;
                    return $out;
                }
            }
        }

        $out['page'] = '404';
        return $out;
    }
}
