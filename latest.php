<?php 
include 'includes/header.php';
?>

<div class="latest-page">
    <div class="container">
        <!-- Page Header -->
        <div class="latest-page-header">
            <h1>Latest News</h1>
            <p>Stay updated with the most recent news and updates</p>
        </div>

        <div class="latest-page-layout">
            <!-- Main Content -->
            <div class="latest-content">
                <!-- Admin Latest News -->
                <section class="latest-section-main">
                    <div class="section-header-latest">
                        <h2>Our Latest Coverage</h2>
                        <span class="news-count">
                            <?php 
                            $count = $conn->query("SELECT COUNT(*) as total FROM news")->fetch_assoc()['total'];
                            echo $count . ' articles';
                            ?>
                        </span>
                    </div>

                    <div class="latest-news-grid">
                        <?php
                        $latestNews = $conn->query("SELECT n.*, c.name as category_name FROM news n LEFT JOIN categories c ON n.category_id = c.id ORDER BY n.created_at DESC LIMIT 12");
                        
                        if ($latestNews && $latestNews->num_rows > 0) {
                            while ($news = $latestNews->fetch_assoc()):
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
                            echo '<p class="no-content">No news available yet.</p>';
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
                        $apiNews = fetchExternalNews('', 10);
                        if ($apiNews && isset($apiNews['results'])):
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
                    $trending = $conn->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 5");
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
                    endif;
                    ?>
                </div>

                <!-- Categories -->
                <div class="sidebar-box">
                    <h3 class="sidebar-box-title">Browse by Category</h3>
                    <div class="category-list">
                        <?php foreach ($categories as $cat): ?>
                            <a href="category.php?slug=<?php echo $cat['slug']; ?>" class="category-link">
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
                            <?php endif; ?>
                            <div class="mini-play-icon">▶</div>
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
