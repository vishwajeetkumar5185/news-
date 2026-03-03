<?php
/**
 * Dynamic Sitemap Generator for Live 18 India
 * This file generates sitemap.xml with all pages, news articles, videos, and categories
 */

require_once 'config/database.php';
$conn = getConnection();

// Get current date for lastmod
$currentDate = date('c');
$baseUrl = 'https://live18india.com';

// Start XML output
header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

    <!-- Homepage - Highest Priority -->
    <url>
        <loc><?php echo $baseUrl; ?>/</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>hourly</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Main Pages -->
    <url>
        <loc><?php echo $baseUrl; ?>/index.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>hourly</changefreq>
        <priority>1.0</priority>
    </url>

    <url>
        <loc><?php echo $baseUrl; ?>/latest.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>hourly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc><?php echo $baseUrl; ?>/videos.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc><?php echo $baseUrl; ?>/about.php</loc>
        <lastmod><?php echo date('c', strtotime('2026-02-28')); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc><?php echo $baseUrl; ?>/contact.php</loc>
        <lastmod><?php echo date('c', strtotime('2026-02-28')); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc><?php echo $baseUrl; ?>/search.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>

    <!-- Legal Pages -->
    <url>
        <loc><?php echo $baseUrl; ?>/privacy-policy.php</loc>
        <lastmod><?php echo date('c', strtotime('2026-02-28')); ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.5</priority>
    </url>

    <url>
        <loc><?php echo $baseUrl; ?>/terms-of-use.php</loc>
        <lastmod><?php echo date('c', strtotime('2026-02-28')); ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.5</priority>
    </url>

    <url>
        <loc><?php echo $baseUrl; ?>/cookie-policy.php</loc>
        <lastmod><?php echo date('c', strtotime('2026-02-28')); ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.5</priority>
    </url>

    <url>
        <loc><?php echo $baseUrl; ?>/sitemap.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>

    <?php
    // Get all categories
    $categories = $conn->query("SELECT * FROM categories WHERE status = 1 ORDER BY name");
    if ($categories && $categories->num_rows > 0) {
        while ($category = $categories->fetch_assoc()) {
            $catLastMod = !empty($category['updated_at']) ? date('c', strtotime($category['updated_at'])) : $currentDate;
    ?>
    <!-- Category: <?php echo htmlspecialchars($category['name']); ?> -->
    <url>
        <loc><?php echo $baseUrl; ?>/category.php?slug=<?php echo urlencode($category['slug']); ?></loc>
        <lastmod><?php echo $catLastMod; ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <?php
        }
    }
    ?>

    <?php
    // Get all news articles
    $news = $conn->query("SELECT n.*, c.name as category_name 
                          FROM news n 
                          LEFT JOIN categories c ON n.category_id = c.id 
                          ORDER BY n.created_at DESC 
                          LIMIT 1000");
    
    if ($news && $news->num_rows > 0) {
        while ($article = $news->fetch_assoc()) {
            $articleLastMod = date('c', strtotime($article['created_at']));
            $articleImage = !empty($article['image']) ? $baseUrl . '/uploads/' . $article['image'] : '';
            
            // Extract YouTube thumbnail if available
            if (!empty($article['youtube_url'])) {
                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $article['youtube_url'], $matches);
                if (isset($matches[1])) {
                    $articleImage = 'https://img.youtube.com/vi/' . $matches[1] . '/maxresdefault.jpg';
                }
            }
    ?>
    <!-- News Article: <?php echo htmlspecialchars($article['title']); ?> -->
    <url>
        <loc><?php echo $baseUrl; ?>/single.php?id=<?php echo $article['id']; ?></loc>
        <lastmod><?php echo $articleLastMod; ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
        
        <?php if (!empty($articleImage)): ?>
        <!-- Image for this article -->
        <image:image>
            <image:loc><?php echo htmlspecialchars($articleImage); ?></image:loc>
            <image:title><?php echo htmlspecialchars($article['title']); ?></image:title>
            <image:caption><?php echo htmlspecialchars(substr($article['subtitle'] ?? '', 0, 200)); ?></image:caption>
        </image:image>
        <?php endif; ?>
        
        <?php if (!empty($article['category_name'])): ?>
        <!-- Google News specific tags -->
        <news:news>
            <news:publication>
                <news:name>Live 18 India</news:name>
                <news:language>hi</news:language>
            </news:publication>
            <news:publication_date><?php echo $articleLastMod; ?></news:publication_date>
            <news:title><?php echo htmlspecialchars($article['title']); ?></news:title>
            <news:keywords><?php echo htmlspecialchars($article['category_name']); ?>, India News, Breaking News</news:keywords>
        </news:news>
        <?php endif; ?>
    </url>
    <?php
        }
    }
    ?>

    <?php
    // Get all videos
    $videos = $conn->query("SELECT * FROM videos WHERE status = 1 ORDER BY id DESC LIMIT 500");
    
    if ($videos && $videos->num_rows > 0) {
        while ($video = $videos->fetch_assoc()) {
            $videoLastMod = !empty($video['created_at']) ? date('c', strtotime($video['created_at'])) : $currentDate;
            
            // Get video thumbnail
            $videoImage = '';
            if (!empty($video['youtube_url'])) {
                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video['youtube_url'], $matches);
                if (isset($matches[1])) {
                    $videoImage = 'https://img.youtube.com/vi/' . $matches[1] . '/maxresdefault.jpg';
                }
            } elseif (!empty($video['thumbnail'])) {
                $videoImage = $baseUrl . '/uploads/' . $video['thumbnail'];
            }
    ?>
    <!-- Video: <?php echo htmlspecialchars($video['title']); ?> -->
    <url>
        <loc><?php echo $baseUrl; ?>/videos.php?id=<?php echo $video['id']; ?></loc>
        <lastmod><?php echo $videoLastMod; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
        
        <?php if (!empty($videoImage)): ?>
        <image:image>
            <image:loc><?php echo htmlspecialchars($videoImage); ?></image:loc>
            <image:title><?php echo htmlspecialchars($video['title']); ?></image:title>
        </image:image>
        <?php endif; ?>
    </url>
    <?php
        }
    }
    ?>

</urlset>
<?php
$conn->close();
?>
