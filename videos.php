<?php 
include 'includes/header.php';
?>

<div class="videos-page">
    <div class="container">
        <!-- Page Header -->
        <div class="videos-page-header">
            <h1>Latest Videos</h1>
            <p>Watch our curated video content</p>
        </div>

        <div class="videos-page-layout">
            <!-- Main Content Area -->
            <div class="videos-content">
                <!-- Admin Videos Section -->
                <section class="videos-section">
                    <div class="section-header-videos">
                        <h2>Our Videos</h2>
                        <span class="video-count">
                            <?php 
                            $count = $conn->query("SELECT COUNT(*) as total FROM videos WHERE status=1")->fetch_assoc()['total'];
                            echo $count . ' videos';
                            ?>
                        </span>
                    </div>

                    <div class="videos-grid-main">
                        <?php
                        $videos = $conn->query("SELECT * FROM videos WHERE status=1 ORDER BY created_at DESC");
                        
                        if ($videos && $videos->num_rows > 0) {
                            while ($video = $videos->fetch_assoc()):
                                $youtube_id = '';
                                if ($video['youtube_url']) {
                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video['youtube_url'], $matches);
                                    $youtube_id = $matches[1] ?? '';
                                }
                        ?>
                            <div class="video-item">
                                <div class="video-thumbnail-wrapper" onclick="openVideoPopup('<?php echo $youtube_id ? $youtube_id : 'uploads/' . $video['video_file']; ?>', '<?php echo $youtube_id ? 'youtube' : 'file'; ?>')">
                                    <div class="video-thumbnail-img">
                                        <?php if ($youtube_id): ?>
                                            <img src="https://img.youtube.com/vi/<?php echo $youtube_id; ?>/maxresdefault.jpg" alt="<?php echo $video['title']; ?>">
                                        <?php elseif ($video['thumbnail']): ?>
                                            <img src="uploads/<?php echo $video['thumbnail']; ?>" alt="<?php echo $video['title']; ?>">
                                        <?php else: ?>
                                            <img src="https://via.placeholder.com/640x360?text=Video" alt="<?php echo $video['title']; ?>">
                                        <?php endif; ?>
                                    </div>
                                    <div class="video-play-btn">
                                        <svg width="68" height="48" viewBox="0 0 68 48"><path d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#f00"></path><path d="M 45,24 27,14 27,34" fill="#fff"></path></svg>
                                    </div>
                                    <div class="video-duration-badge">
                                        <?php echo $youtube_id ? 'YouTube' : 'Video'; ?>
                                    </div>
                                </div>
                                <div class="video-info">
                                    <h3><?php echo $video['title']; ?></h3>
                                    <div class="video-metadata">
                                        <span class="video-date"><?php echo date('M d, Y', strtotime($video['created_at'])); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php
                            endwhile;
                        } else {
                            echo '<div class="no-videos-msg"><p>No videos available yet. Check back soon!</p></div>';
                        }
                        ?>
                    </div>
                </section>

                <!-- Web News Section -->
                <section class="web-news-section">
                    <div class="section-header-videos">
                        <h2>Latest News</h2>
                        <a href="index.php" class="view-all-link">View All →</a>
                    </div>

                    <div class="web-news-list">
                        <?php
                        $apiNews = fetchExternalNews('', 6);
                        if ($apiNews && isset($apiNews['results'])):
                            foreach ($apiNews['results'] as $article):
                        ?>
                            <article class="web-news-item">
                                <a href="<?php echo $article['link']; ?>" target="_blank">
                                    <div class="web-news-thumb">
                                        <?php if (!empty($article['image_url'])): ?>
                                            <img src="<?php echo $article['image_url']; ?>" alt="<?php echo $article['title']; ?>">
                                        <?php else: ?>
                                            <img src="https://via.placeholder.com/200x120?text=News" alt="No Image">
                                        <?php endif; ?>
                                    </div>
                                    <div class="web-news-details">
                                        <span class="news-badge"><?php echo ucfirst(isset($article['category'][0]) ? $article['category'][0] : 'News'); ?></span>
                                        <h4><?php echo $article['title']; ?></h4>
                                        <p><?php echo substr(isset($article['description']) ? $article['description'] : '', 0, 120); ?>...</p>
                                        <span class="news-time"><?php echo date('M d, Y', strtotime($article['pubDate'])); ?></span>
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
            <aside class="videos-sidebar-right">
                <!-- Featured Videos Widget -->
                <div class="sidebar-box">
                    <h3 class="sidebar-box-title">Trending Videos</h3>
                    <?php
                    $trendingVideos = $conn->query("SELECT * FROM videos WHERE status=1 ORDER BY created_at DESC LIMIT 5");
                    if ($trendingVideos && $trendingVideos->num_rows > 0):
                        while ($tv = $trendingVideos->fetch_assoc()):
                            $yt_id = '';
                            if ($tv['youtube_url']) {
                                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $tv['youtube_url'], $m);
                                $yt_id = $m[1] ?? '';
                            }
                    ?>
                        <div class="sidebar-video-item" onclick="openVideoPopup('<?php echo $yt_id ? $yt_id : 'uploads/' . $tv['video_file']; ?>', '<?php echo $yt_id ? 'youtube' : 'file'; ?>')">
                            <div class="sidebar-video-thumb">
                                <?php if ($yt_id): ?>
                                    <img src="https://img.youtube.com/vi/<?php echo $yt_id; ?>/mqdefault.jpg" alt="<?php echo $tv['title']; ?>">
                                <?php elseif ($tv['thumbnail']): ?>
                                    <img src="uploads/<?php echo $tv['thumbnail']; ?>" alt="<?php echo $tv['title']; ?>">
                                <?php endif; ?>
                                <div class="sidebar-play-icon">▶</div>
                            </div>
                            <div class="sidebar-video-info">
                                <h4><?php echo $tv['title']; ?></h4>
                                <span><?php echo date('M d', strtotime($tv['created_at'])); ?></span>
                            </div>
                        </div>
                    <?php
                        endwhile;
                    endif;
                    ?>
                </div>

                <!-- Featured News Widget -->
                <div class="sidebar-box">
                    <h3 class="sidebar-box-title">Featured News</h3>
                    <?php
                    $featured = getFeaturedNews($conn, 4);
                    foreach ($featured as $feat):
                    ?>
                        <div class="sidebar-news-mini">
                            <a href="single.php?id=<?php echo $feat['id']; ?>">
                                <img src="uploads/<?php echo $feat['image']; ?>" alt="<?php echo $feat['title']; ?>">
                                <div>
                                    <h4><?php echo $feat['title']; ?></h4>
                                    <span><?php echo date('M d', strtotime($feat['created_at'])); ?></span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
