<?php
$root = dirname(__DIR__) . '/plugins';
$meta = [
    'adposting' => ['Ad Posting', 'Classified ads ready-made site', 'src/AdpostingPlugin.php'],
    'blog' => ['Blog', 'Blog / portal ready-made site', 'src/BlogPlugin.php'],
    'doctors' => ['Doctors', 'Doctors / clinic directory ready-made site', 'src/DoctorsPlugin.php'],
    'imagegallery' => ['Image Gallery', 'Image gallery ready-made site', 'src/ImageGalleryPlugin.php'],
    'marketplace' => ['Marketplace', 'Marketplace / classifieds ready-made site', 'src/MarketplacePlugin.php'],
    'portfolio' => ['Portfolio', 'Portfolio ready-made site', 'src/PortfolioPlugin.php'],
    'productpublisher' => ['Product Publisher', 'Product catalog ready-made site', 'src/ProductPublisherPlugin.php'],
    'searchengine' => ['Search Engine', 'Search directory ready-made site', 'src/SearchEnginePlugin.php'],
    'tutorials' => ['Tutorials', 'Tutorials / learning ready-made site', 'src/TutorialsPlugin.php'],
    'videostream' => ['Video Stream', 'Video streaming ready-made site', 'src/VideoStreamPlugin.php'],
];

foreach ($meta as $slug => $info) {
    $data = [
        'name' => $info[0],
        'slug' => $slug,
        'type' => 'site',
        'version' => '1.0.0',
        'description' => $info[1],
        'entry' => $info[2],
    ];
    $path = "$root/$slug/plugin.json";
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
    echo "Wrote $path\n";
}
