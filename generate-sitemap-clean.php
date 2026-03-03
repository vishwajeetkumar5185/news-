<?php
/**
 * Clean Sitemap Generator - Direct XML Output
 * Access this file directly to get clean sitemap XML
 */

require_once 'config/database.php';
$conn = getConnection();

$currentDate = date('c');
$baseUrl = 'https://live18india.com';

// Set proper headers
header('Content-Type: application/xml; charset=utf-8');

// Start XML
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
echo '        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"' . "\n";
echo '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n\n";

// Homepage
echo "    <!-- Homepage - Highest Priority -->\n";
echo "    <url>\n";
echo "        <loc>{$baseUrl}/</loc>\n";
echo "        <lastmod>{$currentDate}</lastmod>\n";
echo "        <changefreq>hourly</changefreq>\n";
echo "        <priority>1.0</priority>\n";
echo "    </url>\n\n";

// Main pages
$mainPages = [
    ['url' => 'index.php', 'freq' => 'hourly', 'priority' => '1.0'],
    ['url' => 'latest.php', 'freq' => 'hourly', 'priority' => '0.9'],
    ['url' => 'videos.php', 'freq' => 'daily', 'priority' => '0.9'],
    ['url' => 'about.php', 'freq' => 'monthly', 'priority' => '0.8'],
    ['url' => 'contact.php', 'freq' => 'monthly', 'priority' => '0.8'],
    ['url' => 'search.php', 'freq' => 'weekly', 'priority' => '0.6'],
    ['url' => 'privacy-policy.php', 'freq' => 'yearly', 'priority' => '0.5'],
    ['url' => 'terms-of-use.php', 'freq' => 'yearly', 'priority' => '0.5'],
    ['url' => 'cookie-policy.php', 'freq' => 'yearly', 'priority' => '0.5'],
    ['url' => 'sitemap.php', 'freq' => 'weekly', 'priority' => '0.6']
];

echo "    <!-- Main Pages -->\n";
foreach ($mainPages as $page) {
    echo "    <url>\n";
    echo "        <loc>{$baseUrl}/{$page['url']}</loc>\n";
    echo "        <lastmod>{$currentDate}</lastmod>\n";
    echo "        <changefreq>{$page['freq']}</changefreq>\n";
    echo "        <priority>{$page['priority']}</priority>\n";
    echo "    </url>\n\n";
}

// Categories
$categories = $conn->query("SELECT * FROM categories WHERE status = 1 ORDER BY name");
if ($categories && $categories->num_rows > 0) {
    echo "    <!-- Categories -->\n";
    while ($category = $categories->fetch_assoc()) {
        $catLastMod = !empty($category['updated_at']) ? date('c', strtotime($category['updated_at'])) : $currentDate;
        echo "    <url>\n";
        echo "        <loc>{$baseUrl}/category.php?slug=" . urlencode($category['slug']) . "</loc>\n";
        echo "        <lastmod>{$catLastMod}</lastmod>\n";
        echo "        <changefreq>daily</changefreq>\n";
        echo "        <priority>0.8</priority>\n";
        echo "    </url>\n\n";
    }
}

// News Articles
$news = $conn->query("SELECT n.*, c.name as category_name 
                      FROM news n 
                      LEFT JOIN categories c ON n.category_id = c.id 
                      ORDER BY n.created_at DESC 
                      LIMIT 1000");

if ($news && $news->num_rows > 0) {
    echo "    <!-- News Articles -->\n";
    while ($article = $news->fetch_assoc()) {
        $articleLastMod = date('c', strtotime($article['created_at']));
        $articleImage = '';
        
        // Get image URL
        if (!empty($article['image'])) {
            $articleImage = $baseUrl . '/uploads/' . $article['image'];
        } elseif (!empty($article['youtube_url'])) {
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $article['youtube_url'], $matches);
            if (isset($matches[1])) {
                $articleImage = 'https://img.youtube.com/vi/' . $matches[1] . '/maxresdefault.jpg';
            }
        }
        
        echo "    <url>\n";
        echo "        <loc>{$baseUrl}/single.php?id={$article['id']}</loc>\n";
        echo "        <lastmod>{$articleLastMod}</lastmod>\n";
        echo "        <changefreq>weekly</changefreq>\n";
        echo "        <priority>0.7</priority>\n";
        
        // Add image if available
        if (!empty($articleImage)) {
            echo "        <image:image>\n";
            echo "            <image:loc>" . htmlspecialchars($articleImage) . "</image:loc>\n";
            echo "            <image:title>" . htmlspecialchars($article['title']) . "</image:title>\n";
            if (!empty($article['subtitle'])) {
                echo "            <image:caption>" . htmlspecialchars(substr($article['subtitle'], 0, 200)) . "</image:caption>\n";
            }
            echo "        </image:image>\n";
        }
        
        // Add Google News tags
        if (!empty($article['category_name'])) {
            echo "        <news:news>\n";
            echo "            <news:publication>\n";
            echo "                <news:name>Live 18 India</news:name>\n";
            echo "                <news:language>hi</news:language>\n";
            echo "            </news:publication>\n";
            echo "            <news:publication_date>{$articleLastMod}</news:publication_date>\n";
            echo "            <news:title>" . htmlspecialchars($article['title']) . "</news:title>\n";
            echo "            <news:keywords>" . htmlspecialchars($article['category_name']) . ", India News, Breaking News</news:keywords>\n";
            echo "        </news:news>\n";
        }
        
        echo "    </url>\n\n";
    }
}

// Videos
$videos = $conn->query("SELECT * FROM videos WHERE status = 1 ORDER BY id DESC LIMIT 500");

if ($videos && $videos->num_rows > 0) {
    echo "    <!-- Videos -->\n";
    while ($video = $videos->fetch_assoc()) {
        $videoLastMod = !empty($video['created_at']) ? date('c', strtotime($video['created_at'])) : $currentDate;
        $videoImage = '';
        
        if (!empty($video['youtube_url'])) {
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video['youtube_url'], $matches);
            if (isset($matches[1])) {
                $videoImage = 'https://img.youtube.com/vi/' . $matches[1] . '/maxresdefault.jpg';
            }
        } elseif (!empty($video['thumbnail'])) {
            $videoImage = $baseUrl . '/uploads/' . $video['thumbnail'];
        }
        
        echo "    <url>\n";
        echo "        <loc>{$baseUrl}/videos.php?id={$video['id']}</loc>\n";
        echo "        <lastmod>{$videoLastMod}</lastmod>\n";
        echo "        <changefreq>monthly</changefreq>\n";
        echo "        <priority>0.6</priority>\n";
        
        if (!empty($videoImage)) {
            echo "        <image:image>\n";
            echo "            <image:loc>" . htmlspecialchars($videoImage) . "</image:loc>\n";
            echo "            <image:title>" . htmlspecialchars($video['title']) . "</image:title>\n";
            echo "        </image:image>\n";
        }
        
        echo "    </url>\n\n";
    }
}

echo "</urlset>\n";

$conn->close();
?>
