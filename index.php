<?php include 'includes/header.php'; ?>

<div class="home-wrapper">
    <div class="container">
        <div class="home-layout">
            <!-- Left Sidebar - Featured News & Banners -->
            <aside class="left-sidebar">
                <div class="section-header">
                    <h2 class="section-title-small">Featured News</h2>
                </div>
                
                <?php
                $featured = getFeaturedNews($conn, 3);
                foreach ($featured as $news):
                ?>
                    <article class="featured-item">
                        <a href="single.php?id=<?php echo $news['id']; ?>" class="featured-item-link">
                            <div class="featured-item-image">
                                <img src="uploads/<?php echo $news['image']; ?>" alt="<?php echo $news['title']; ?>">
                            </div>
                            <div class="featured-item-content">
                                <h4><?php echo $news['title']; ?></h4>
                                <span class="featured-date"><?php echo date('M d, Y', strtotime($news['created_at'])); ?></span>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
                
                <!-- Banners Section -->
                <div class="sidebar-banners">
                    <h3 class="section-title-small">Advertisement</h3>
                    <?php
                    $banners = getBanners($conn, 'sidebar');
                    foreach ($banners as $banner):
                    ?>
                        <div class="sidebar-banner">
                            <a href="<?php echo $banner['link']; ?>" target="_blank">
                                <img src="uploads/<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </aside>

            <!-- Middle & Right - Latest Headlines -->
            <div class="main-content-area">
                <section class="latest-section">
                    <div class="section-header">
                        <h2 class="section-title">Latest Headlines</h2>
                        <div class="filter-tabs">
                            <button class="tab-btn active">All</button>
                            <button class="tab-btn">India</button>
                            <button class="tab-btn">World</button>
                            <button class="tab-btn">Sports</button>
                        </div>
                    </div>

                    <div class="latest-grid">
                        <?php
                        // Fetch API news
                        $apiNews = fetchExternalNews('', 10);
                        $allNews = array();
                        
                        // Add API news to array
                        if (isset($apiNews['results']) && count($apiNews['results']) > 0) {
                            foreach ($apiNews['results'] as $article) {
                                $allNews[] = array(
                                    'type' => 'api',
                                    'title' => $article['title'],
                                    'description' => isset($article['description']) ? $article['description'] : '',
                                    'image_url' => isset($article['image_url']) ? $article['image_url'] : '',
                                    'link' => $article['link'],
                                    'category' => isset($article['category'][0]) ? $article['category'][0] : 'Breaking',
                                    'pubDate' => $article['pubDate']
                                );
                            }
                        }
                        
                        // Fetch admin uploaded videos/news
                        $adminVideos = $conn->query("SELECT * FROM videos WHERE status=1 ORDER BY created_at DESC LIMIT 5");
                        if ($adminVideos && $adminVideos->num_rows > 0) {
                            while ($video = $adminVideos->fetch_assoc()) {
                                // Extract YouTube video ID
                                $youtube_id = '';
                                if ($video['youtube_url']) {
                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video['youtube_url'], $matches);
                                    $youtube_id = $matches[1] ?? '';
                                }
                                
                                $allNews[] = array(
                                    'type' => 'video',
                                    'id' => $video['id'],
                                    'title' => $video['title'],
                                    'description' => '',
                                    'image_url' => $video['thumbnail'] ? 'uploads/' . $video['thumbnail'] : '',
                                    'video_file' => $video['video_file'],
                                    'youtube_id' => $youtube_id,
                                    'category' => 'Video',
                                    'pubDate' => $video['created_at']
                                );
                            }
                        }
                        
                        // Display all news
                        if (count($allNews) > 0) {
                            foreach ($allNews as $item) {
                        ?>
                            <article class="latest-card">
                                <?php if ($item['type'] == 'video'): ?>
                                    <!-- Video Card -->
                                    <div class="latest-card-link" style="cursor: pointer;" onclick="openVideoPopup('<?php echo $item['youtube_id'] ? $item['youtube_id'] : 'uploads/' . $item['video_file']; ?>', '<?php echo $item['youtube_id'] ? 'youtube' : 'file'; ?>')">
                                        <div class="latest-card-image video-preview">
                                            <?php if ($item['youtube_id']): ?>
                                                <img src="https://img.youtube.com/vi/<?php echo $item['youtube_id']; ?>/maxresdefault.jpg" alt="<?php echo $item['title']; ?>">
                                            <?php elseif ($item['image_url']): ?>
                                                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['title']; ?>">
                                            <?php else: ?>
                                                <img src="https://via.placeholder.com/800x300?text=Video" alt="<?php echo $item['title']; ?>">
                                            <?php endif; ?>
                                            <div class="video-play-overlay">
                                                <div class="play-button">▶</div>
                                            </div>
                                        </div>
                                        <div class="latest-card-content">
                                            <span class="card-category">Video</span>
                                            <h3><?php echo $item['title']; ?></h3>
                                            <div class="card-footer">
                                                <span class="card-date"><?php echo date('M d, Y h:i A', strtotime($item['pubDate'])); ?></span>
                                                <span class="card-source">Admin Upload</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <!-- API News Card -->
                                    <a href="<?php echo $item['link']; ?>" target="_blank" class="latest-card-link">
                                        <div class="latest-card-image">
                                            <?php if (!empty($item['image_url'])): ?>
                                                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['title']; ?>">
                                            <?php else: ?>
                                                <img src="https://via.placeholder.com/800x300?text=News" alt="No Image">
                                            <?php endif; ?>
                                            <div class="card-overlay">
                                                <span class="read-more">Read More →</span>
                                            </div>
                                        </div>
                                        <div class="latest-card-content">
                                            <span class="card-category"><?php echo ucfirst($item['category']); ?></span>
                                            <h3><?php echo $item['title']; ?></h3>
                                            <p><?php echo substr($item['description'], 0, 200); ?>...</p>
                                            <div class="card-footer">
                                                <span class="card-date"><?php echo date('M d, Y h:i A', strtotime($item['pubDate'])); ?></span>
                                                <span class="card-source">NewsData.io</span>
                                            </div>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            </article>
                        <?php
                            }
                        } else {
                            echo '<p style="text-align: center; padding: 40px;">No news available at the moment.</p>';
                        }
                        ?>
                    </div>
                </section>
            </div>

            <!-- Right Sidebar - Latest Videos -->
            <aside class="right-sidebar">
                <div class="sidebar-sticky">
                    <h3 class="sidebar-title">Latest Videos</h3>
                    <?php
                    $videos = $conn->query("SELECT * FROM videos WHERE status=1 ORDER BY id DESC LIMIT 5");
                    if ($videos && $videos->num_rows > 0):
                        while ($video = $videos->fetch_assoc()):
                            // Extract YouTube video ID
                            $youtube_id = '';
                            if ($video['youtube_url']) {
                                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video['youtube_url'], $matches);
                                $youtube_id = $matches[1] ?? '';
                            }
                    ?>
                        <div class="video-widget" data-video-id="<?php echo $video['id']; ?>">
                            <?php if ($youtube_id): ?>
                                <div class="video-thumbnail" onclick="openVideoPopup('<?php echo $youtube_id; ?>', 'youtube')">
                                    <iframe width="100%" height="180" src="https://www.youtube.com/embed/<?php echo $youtube_id; ?>?autoplay=1&mute=1&loop=1&playlist=<?php echo $youtube_id; ?>&controls=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                </div>
                            <?php elseif ($video['video_file']): ?>
                                <div class="video-thumbnail" onclick="openVideoPopup('uploads/<?php echo $video['video_file']; ?>', 'file')">
                                    <video width="100%" height="180" autoplay muted loop playsinline>
                                        <source src="uploads/<?php echo $video['video_file']; ?>" type="video/mp4">
                                    </video>
                                </div>
                            <?php endif; ?>
                            <h4 class="video-title"><?php echo $video['title']; ?></h4>
                        </div>
                    <?php
                        endwhile;
                    endif;
                    ?>
                    
                    <!-- Trending Topics -->
                    <div class="trending-widget">
                        <h3 class="sidebar-title">Trending Topics</h3>
                        <div class="trending-list">
                            <?php
                            // Get unique categories from API news
                            if (isset($apiNews['results']) && count($apiNews['results']) > 0) {
                                $categories = array();
                                foreach ($apiNews['results'] as $article) {
                                    if (isset($article['category']) && is_array($article['category'])) {
                                        foreach ($article['category'] as $cat) {
                                            if (!in_array($cat, $categories)) {
                                                $categories[] = $cat;
                                            }
                                        }
                                    }
                                }
                                
                                // Display unique categories
                                foreach (array_slice($categories, 0, 8) as $cat) {
                                    echo '<a href="#" class="trending-tag">#' . ucfirst($cat) . '</a>';
                                }
                            } else {
                                // Fallback trending topics
                                echo '<a href="#" class="trending-tag">#IndiaNews</a>';
                                echo '<a href="#" class="trending-tag">#Politics</a>';
                                echo '<a href="#" class="trending-tag">#Sports</a>';
                                echo '<a href="#" class="trending-tag">#Technology</a>';
                                echo '<a href="#" class="trending-tag">#Entertainment</a>';
                                echo '<a href="#" class="trending-tag">#Business</a>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
