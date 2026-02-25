<?php 
include 'includes/header.php';

$slug = $_GET['slug'] ?? '';
$category = $conn->query("SELECT * FROM categories WHERE slug = '$slug'")->fetch_assoc();

if (!$category) {
    header('Location: index.php');
    exit;
}
?>

<div class="category-page">
    <div class="container">
        <div class="category-header">
            <h1 class="category-title"><?php echo $category['name']; ?></h1>
            <p class="category-description">Latest news and updates from <?php echo $category['name']; ?></p>
        </div>

        <div class="category-layout">
            <!-- Main Content - Category News -->
            <div class="category-main">
                <div class="news-grid-category">
                    <?php
                    // Fetch news from database
                    $dbNews = $conn->query("SELECT * FROM news WHERE category_id = {$category['id']} ORDER BY created_at DESC");
                    
                    if ($dbNews && $dbNews->num_rows > 0) {
                        while ($news = $dbNews->fetch_assoc()):
                    ?>
                        <article class="category-card">
                            <a href="single.php?id=<?php echo $news['id']; ?>" class="category-card-link">
                                <div class="category-card-image">
                                    <img src="uploads/<?php echo $news['image']; ?>" alt="<?php echo $news['title']; ?>">
                                    <div class="card-overlay">
                                        <span class="read-more">Read More →</span>
                                    </div>
                                </div>
                                <div class="category-card-content">
                                    <span class="card-category"><?php echo $category['name']; ?></span>
                                    <h3><?php echo $news['title']; ?></h3>
                                    <?php if (isset($news['subtitle']) && $news['subtitle']): ?>
                                        <p class="subtitle"><?php echo $news['subtitle']; ?></p>
                                    <?php endif; ?>
                                    <p><?php echo substr(strip_tags($news['content']), 0, 150); ?>...</p>
                                    <div class="card-footer">
                                        <span class="card-date"><?php echo date('M d, Y h:i A', strtotime($news['created_at'])); ?></span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    <?php
                        endwhile;
                    } else {
                        echo '<p class="no-news">No news available in this category yet.</p>';
                    }
                    ?>
                </div>

                <!-- API News Section -->
                <div class="api-news-section">
                    <h2 class="section-title">More from Web</h2>
                    <div class="api-news-grid">
                        <?php
                        $apiNews = fetchExternalNews($slug, 6);
                        if ($apiNews && isset($apiNews['results'])):
                            foreach ($apiNews['results'] as $article):
                        ?>
                            <article class="api-news-card">
                                <a href="<?php echo $article['link']; ?>" target="_blank">
                                    <?php if (!empty($article['image_url'])): ?>
                                        <img src="<?php echo $article['image_url']; ?>" alt="<?php echo $article['title']; ?>">
                                    <?php else: ?>
                                        <img src="https://via.placeholder.com/300x200?text=News" alt="No Image">
                                    <?php endif; ?>
                                    <h4><?php echo $article['title']; ?></h4>
                                    <span class="api-date"><?php echo date('M d, Y', strtotime($article['pubDate'])); ?></span>
                                </a>
                            </article>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="category-sidebar">
                <!-- Featured News -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">Featured News</h3>
                    <?php
                    $featured = getFeaturedNews($conn, 4);
                    foreach ($featured as $feat):
                    ?>
                        <article class="sidebar-news-item">
                            <a href="single.php?id=<?php echo $feat['id']; ?>">
                                <img src="uploads/<?php echo $feat['image']; ?>" alt="<?php echo $feat['title']; ?>">
                                <h4><?php echo $feat['title']; ?></h4>
                                <span class="item-date"><?php echo date('M d, Y', strtotime($feat['created_at'])); ?></span>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- Latest Videos -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">Latest Videos</h3>
                    <?php
                    $videos = $conn->query("SELECT * FROM videos WHERE status=1 ORDER BY id DESC LIMIT 3");
                    if ($videos && $videos->num_rows > 0):
                        while ($video = $videos->fetch_assoc()):
                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video['youtube_url'], $matches);
                            $youtube_id = $matches[1] ?? '';
                    ?>
                        <div class="sidebar-video" onclick="openVideoPopup('<?php echo $youtube_id ? $youtube_id : 'uploads/' . $video['video_file']; ?>', '<?php echo $youtube_id ? 'youtube' : 'file'; ?>')">
                            <?php if ($youtube_id): ?>
                                <img src="https://img.youtube.com/vi/<?php echo $youtube_id; ?>/mqdefault.jpg" alt="<?php echo $video['title']; ?>">
                            <?php elseif ($video['thumbnail']): ?>
                                <img src="uploads/<?php echo $video['thumbnail']; ?>" alt="<?php echo $video['title']; ?>">
                            <?php endif; ?>
                            <div class="video-play-icon">▶</div>
                            <h4><?php echo $video['title']; ?></h4>
                        </div>
                    <?php
                        endwhile;
                    endif;
                    ?>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
