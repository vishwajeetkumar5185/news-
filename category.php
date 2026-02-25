<?php 
// Include necessary files first
require_once 'config/database.php';
require_once 'config/functions.php';
$conn = getConnection();

// Check category before including header
$slug = $_GET['slug'] ?? '';
$category = $conn->query("SELECT * FROM categories WHERE slug = '$slug'")->fetch_assoc();

if (!$category) {
    header('Location: index.php');
    exit;
}

// Now include header after validation
include 'includes/header.php';
?>

<div class="latest-page">
    <div class="container">
        <!-- Page Header -->
        <div class="latest-page-header">
            <h1><?php echo $category['name']; ?></h1>
            <p>Latest news and updates from <?php echo $category['name']; ?></p>
        </div>

        <div class="latest-page-layout">
            <!-- Main Content -->
            <div class="latest-content">
                <!-- Category News from Database -->
                <section class="latest-section-main">
                    <div class="section-header-latest">
                        <h2><?php echo $category['name']; ?> Coverage</h2>
                        <span class="news-count">
                            <?php 
                            $count = $conn->query("SELECT COUNT(*) as total FROM news WHERE category_id = {$category['id']}")->fetch_assoc()['total'];
                            echo $count . ' articles';
                            ?>
                        </span>
                    </div>

                    <div class="latest-news-grid">
                        <?php
                        $dbNews = $conn->query("SELECT n.*, c.name as category_name FROM news n LEFT JOIN categories c ON n.category_id = c.id WHERE n.category_id = {$category['id']} ORDER BY n.created_at DESC");
                        
                        if ($dbNews && $dbNews->num_rows > 0) {
                            while ($news = $dbNews->fetch_assoc()):
                        ?>
                            <article class="latest-news-card">
                                <a href="single.php?id=<?php echo $news['id']; ?>" class="latest-news-link">
                                    <div class="latest-news-image">
                                        <img src="uploads/<?php echo $news['image']; ?>" alt="<?php echo $news['title']; ?>">
                                        <div class="news-overlay">
                                            <span class="read-btn">Read Article →</span>
                                        </div>
                                    </div>
                                    <div class="latest-news-content">
                                        <span class="news-category-badge"><?php echo $news['category_name']; ?></span>
                                        <h3><?php echo $news['title']; ?></h3>
                                        <?php if (isset($news['subtitle']) && $news['subtitle']): ?>
                                            <p class="news-subtitle"><?php echo $news['subtitle']; ?></p>
                                        <?php endif; ?>
                                        <p class="news-excerpt"><?php echo substr(strip_tags($news['content']), 0, 120); ?>...</p>
                                        <div class="news-footer">
                                            <span class="news-date"><?php echo date('M d, Y h:i A', strtotime($news['created_at'])); ?></span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        <?php
                            endwhile;
                        } else {
                            echo '<p class="no-content">No news available in this category yet.</p>';
                        }
                        ?>
                    </div>
                </section>

                <!-- API Latest News -->
                <section class="api-latest-section">
                    <div class="section-header-latest">
                        <h2>Latest from Around the Web</h2>
                        <span class="api-badge">Live Updates</span>
                    </div>

                    <div class="api-latest-grid">
                        <?php
                        $apiNews = fetchExternalNews($slug, 10);
                        if ($apiNews && isset($apiNews['results']) && count($apiNews['results']) > 0):
                            foreach ($apiNews['results'] as $article):
                        ?>
                            <article class="api-latest-card">
                                <a href="<?php echo $article['link']; ?>" target="_blank">
                                    <div class="api-latest-image">
                                        <?php if (!empty($article['image_url'])): ?>
                                            <img src="<?php echo $article['image_url']; ?>" alt="<?php echo $article['title']; ?>">
                                        <?php else: ?>
                                            <img src="https://via.placeholder.com/400x250?text=News" alt="No Image">
                                        <?php endif; ?>
                                        <div class="api-overlay">
                                            <span class="external-icon">🔗</span>
                                        </div>
                                    </div>
                                    <div class="api-latest-content">
                                        <span class="api-category"><?php echo ucfirst(isset($article['category'][0]) ? $article['category'][0] : 'Breaking'); ?></span>
                                        <h4><?php echo $article['title']; ?></h4>
                                        <p><?php echo substr(isset($article['description']) ? $article['description'] : '', 0, 100); ?>...</p>
                                        <div class="api-footer">
                                            <span class="api-date"><?php echo date('M d, Y h:i A', strtotime($article['pubDate'])); ?></span>
                                            <span class="api-source">External</span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        <?php
                            endforeach;
                        else:
                        ?>
                            <p class="no-content">No latest news available at the moment.</p>
                        <?php
                        endif;
                        ?>
                    </div>
                </section>
            </div>

            <!-- Sidebar -->
            <aside class="latest-sidebar">
                <!-- Trending Now -->
                <div class="sidebar-box">
                    <h3 class="sidebar-box-title">Trending Now</h3>
                    <?php
                    $trending = $conn->query("SELECT * FROM news WHERE category_id = {$category['id']} ORDER BY created_at DESC LIMIT 5");
                    if ($trending && $trending->num_rows > 0):
                        while ($trend = $trending->fetch_assoc()):
                    ?>
                        <div class="trending-item">
                            <a href="single.php?id=<?php echo $trend['id']; ?>">
                                <img src="uploads/<?php echo $trend['image']; ?>" alt="<?php echo $trend['title']; ?>">
                                <div>
                                    <h4><?php echo $trend['title']; ?></h4>
                                    <span><?php echo date('M d, h:i A', strtotime($trend['created_at'])); ?></span>
                                </div>
                            </a>
                        </div>
                    <?php
                        endwhile;
                    else:
                    ?>
                        <p style="text-align: center; color: #999; padding: 20px; font-size: 13px;">No trending news</p>
                    <?php
                    endif;
                    ?>
                </div>

                <!-- Categories -->
                <div class="sidebar-box">
                    <h3 class="sidebar-box-title">Browse by Category</h3>
                    <div class="category-list">
                        <?php foreach ($categories as $cat): ?>
                            <a href="category.php?slug=<?php echo $cat['slug']; ?>" class="category-link <?php echo $cat['slug'] == $slug ? 'active' : ''; ?>">
                                <?php echo $cat['name']; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Latest Videos -->
                <div class="sidebar-box">
                    <h3 class="sidebar-box-title">Latest Videos</h3>
                    <?php
                    $videos = $conn->query("SELECT * FROM videos WHERE status=1 ORDER BY created_at DESC LIMIT 3");
                    if ($videos && $videos->num_rows > 0):
                        while ($video = $videos->fetch_assoc()):
                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video['youtube_url'], $matches);
                            $youtube_id = $matches[1] ?? '';
                    ?>
                        <div class="sidebar-video-mini" onclick="openVideoPopup('<?php echo $youtube_id ? $youtube_id : 'uploads/' . $video['video_file']; ?>', '<?php echo $youtube_id ? 'youtube' : 'file'; ?>')">
                            <?php if ($youtube_id): ?>
                                <img src="https://img.youtube.com/vi/<?php echo $youtube_id; ?>/mqdefault.jpg" alt="<?php echo $video['title']; ?>">
                            <?php elseif ($video['thumbnail']): ?>
                                <img src="uploads/<?php echo $video['thumbnail']; ?>" alt="<?php echo $video['title']; ?>">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/320x180?text=Video" alt="<?php echo $video['title']; ?>">
                            <?php endif; ?>
                            <div class="mini-play-icon">▶</div>
                            <h4><?php echo $video['title']; ?></h4>
                        </div>
                    <?php
                        endwhile;
                    else:
                    ?>
                        <p style="text-align: center; color: #999; padding: 20px; font-size: 13px;">No videos available</p>
                    <?php
                    endif;
                    ?>
                </div>

                <!-- API News Sidebar -->
                <div class="sidebar-box">
                    <h3 class="sidebar-box-title">
                        <span>Breaking News</span>
                        <span class="live-indicator">🔴 LIVE</span>
                    </h3>
                    <?php
                    $sidebarApiNews = fetchExternalNews($slug, 5);
                    if ($sidebarApiNews && isset($sidebarApiNews['results']) && count($sidebarApiNews['results']) > 0):
                        foreach ($sidebarApiNews['results'] as $index => $apiArticle):
                            if ($index >= 5) break; // Limit to 5 articles
                    ?>
                        <div class="sidebar-api-item">
                            <a href="<?php echo $apiArticle['link']; ?>" target="_blank" class="sidebar-api-link">
                                <div class="sidebar-api-image">
                                    <?php if (!empty($apiArticle['image_url'])): ?>
                                        <img src="<?php echo $apiArticle['image_url']; ?>" alt="<?php echo $apiArticle['title']; ?>">
                                    <?php else: ?>
                                        <div class="no-image-placeholder">📰</div>
                                    <?php endif; ?>
                                    <div class="external-badge">EXT</div>
                                </div>
                                <div class="sidebar-api-content">
                                    <h4><?php echo substr($apiArticle['title'], 0, 80); ?><?php echo strlen($apiArticle['title']) > 80 ? '...' : ''; ?></h4>
                                    <div class="sidebar-api-meta">
                                        <span class="api-time"><?php echo date('M d, h:i A', strtotime($apiArticle['pubDate'])); ?></span>
                                        <span class="api-category-small"><?php echo ucfirst(isset($apiArticle['category'][0]) ? $apiArticle['category'][0] : 'News'); ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php
                        endforeach;
                    else:
                    ?>
                        <div class="no-api-news">
                            <div class="no-news-icon">📡</div>
                            <p>No external news available</p>
                            <small>Check back later for updates</small>
                        </div>
                    <?php
                    endif;
                    ?>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
