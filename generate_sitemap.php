<?php
header('Content-Type: application/xml; charset=utf-8');
require_once 'config/database.php';

// Get current date for lastmod
$currentDate = date('c');
$baseUrl = 'https://live18india.com';

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

    <!-- Homepage -->
    <url>
        <loc><?php echo $baseUrl; ?>/</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>hourly</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Main Pages -->
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
        <priority>0.8</priority>
    </url>

    <url>
        <loc><?php echo $baseUrl; ?>/about.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>

    <url>
        <loc><?php echo $baseUrl; ?>/contact.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>

    <url>
        <loc><?php echo $baseUrl; ?>/search.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>

    <!-- Legal Pages -->
    <url>
        <loc><?php echo $baseUrl; ?>/privacy-policy.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <url>
        <loc><?php echo $baseUrl; ?>/terms-of-use.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <url>
        <loc><?php echo $baseUrl; ?>/cookie-policy.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <url>
        <loc><?php echo $baseUrl; ?>/sitemap.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.4</priority>
    </url>

    <?php
    // Fetch and include categories
    $conn = getConnection();
    $cat_query = "SELECT id, name, created_at FROM categories ORDER BY name ASC";
    $cat_result = $conn->query($cat_query);
    
    if ($cat_result && $cat_result->num_rows > 0) {
        while ($category = $cat_result->fetch_assoc()) {
            $categoryUrl = $baseUrl . '/category.php?id=' . $category['id'];
            $categoryDate = isset($category['created_at']) ? date('c', strtotime($category['created_at'])) : $currentDate;
            ?>
    <!-- Category: <?php echo htmlspecialchars($category['name']); ?> -->
    <url>
        <loc><?php echo htmlspecialchars($categoryUrl); ?></loc>
        <lastmod><?php echo $categoryDate; ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.7</priority>
    </url>
            <?php
        }
    }

    // Fetch and include recent news articles (last 100)
    $news_query = "SELECT id, title, created_at, image FROM news ORDER BY created_at DESC LIMIT 100";
    $news_result = $conn->query($news_query);
    
    if ($news_result && $news_result->num_rows > 0) {
        while ($news = $news_result->fetch_assoc()) {
            $newsUrl = $baseUrl . '/single.php?id=' . $news['id'];
            $newsDate = date('c', strtotime($news['created_at']));
            $isRecent = (time() - strtotime($news['created_at'])) < (2 * 24 * 60 * 60); // Last 2 days
            ?>
    <!-- News Article: <?php echo htmlspecialchars($news['title']); ?> -->
    <url>
        <loc><?php echo htmlspecialchars($newsUrl); ?></loc>
        <lastmod><?php echo $newsDate; ?></lastmod>
        <changefreq><?php echo $isRecent ? 'hourly' : 'weekly'; ?></changefreq>
        <priority><?php echo $isRecent ? '0.8' : '0.6'; ?></priority>
        
        <?php if ($isRecent): ?>
        <!-- Google News Sitemap -->
        <news:news>
            <news:publication>
                <news:name>Live 18 India</news:name>
                <news:language>en</news:language>
            </news:publication>
            <news:publication_date><?php echo $newsDate; ?></news:publication_date>
            <news:title><?php echo htmlspecialchars($news['title']); ?></news:title>
        </news:news>
        <?php endif; ?>
        
        <?php if (!empty($news['image'])): ?>
        <!-- Image Sitemap -->
        <image:image>
            <image:loc><?php echo $baseUrl . '/uploads/' . htmlspecialchars($news['image']); ?></image:loc>
            <image:title><?php echo htmlspecialchars($news['title']); ?></image:title>
            <image:caption><?php echo htmlspecialchars($news['title']); ?></image:caption>
        </image:image>
        <?php endif; ?>
    </url>
            <?php
        }
    }

    // Fetch and include videos
    $video_query = "SELECT id, title, created_at, thumbnail FROM videos WHERE status = 1 ORDER BY created_at DESC LIMIT 50";
    $video_result = $conn->query($video_query);
    
    if ($video_result && $video_result->num_rows > 0) {
        while ($video = $video_result->fetch_assoc()) {
            $videoUrl = $baseUrl . '/videos.php?id=' . $video['id'];
            $videoDate = date('c', strtotime($video['created_at']));
            ?>
    <!-- Video: <?php echo htmlspecialchars($video['title']); ?> -->
    <url>
        <loc><?php echo htmlspecialchars($videoUrl); ?></loc>
        <lastmod><?php echo $videoDate; ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
        
        <?php if (!empty($video['thumbnail'])): ?>
        <!-- Video Thumbnail -->
        <image:image>
            <image:loc><?php echo $baseUrl . '/uploads/' . htmlspecialchars($video['thumbnail']); ?></image:loc>
            <image:title><?php echo htmlspecialchars($video['title']); ?></image:title>
        </image:image>
        <?php endif; ?>
    </url>
            <?php
        }
    }
    ?>

</urlset>