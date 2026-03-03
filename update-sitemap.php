<?php
/**
 * Update Sitemap Script
 * Run this file to generate/update sitemap.xml with latest content
 */

echo "<!DOCTYPE html>
<html>
<head>
    <title>Sitemap Update - Live 18 India</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .btn:hover { background: #5568d3; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🗺️ Sitemap Update Tool</h1>";

// Start output buffering to capture sitemap content
ob_start();
include 'sitemap-generator.php';
$sitemapContent = ob_get_clean();

// Save to sitemap.xml
$result = file_put_contents('sitemap.xml', $sitemapContent);

if ($result !== false) {
    echo "<div class='success'>
            <strong>✓ Success!</strong> Sitemap.xml has been updated successfully!<br>
            <strong>File Size:</strong> " . number_format($result) . " bytes<br>
            <strong>Updated:</strong> " . date('Y-m-d H:i:s') . "
          </div>";
    
    // Count URLs
    $urlCount = substr_count($sitemapContent, '<url>');
    $newsCount = substr_count($sitemapContent, '<news:news>');
    $imageCount = substr_count($sitemapContent, '<image:image>');
    
    echo "<div class='info'>
            <strong>📊 Sitemap Statistics:</strong><br>
            • Total URLs: <strong>{$urlCount}</strong><br>
            • News Articles: <strong>{$newsCount}</strong><br>
            • Images: <strong>{$imageCount}</strong>
          </div>";
    
    echo "<div class='info'>
            <strong>📋 Next Steps:</strong><br>
            1. Verify sitemap at: <a href='sitemap.xml' target='_blank'>sitemap.xml</a><br>
            2. Submit to Google Search Console<br>
            3. Submit to Bing Webmaster Tools<br>
            4. Add to robots.txt (already done ✓)
          </div>";
    
    echo "<div style='margin-top: 30px;'>
            <a href='sitemap.xml' class='btn' target='_blank'>View Sitemap</a>
            <a href='index.php' class='btn'>Go to Homepage</a>
            <a href='verify_implementation.php' class='btn'>Verify Implementation</a>
          </div>";
    
    // Show first few lines of sitemap
    $lines = explode("\n", $sitemapContent);
    $preview = implode("\n", array_slice($lines, 0, 30));
    echo "<h3>Sitemap Preview (First 30 lines):</h3>";
    echo "<pre>" . htmlspecialchars($preview) . "\n... (truncated)</pre>";
    
} else {
    echo "<div class='error'>
            <strong>✗ Error!</strong> Failed to write sitemap.xml file.<br>
            Please check file permissions for the root directory.
          </div>";
}

echo "    </div>
</body>
</html>";
?>
