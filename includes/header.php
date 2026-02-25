<?php
require_once 'config/database.php';
require_once 'config/functions.php';
$conn = getConnection();
$settings = getSiteSettings($conn);
$categories = getCategories($conn);
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $settings['site_name'] ?? 'News Portal'; ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <!-- Top Header Bar -->
        <div class="top-header">
            <div class="container">
                <div class="top-left">
                    <span class="edition-label">English Edition</span>
                    <span class="date-display"><?php echo date('D, M d, Y'); ?></span>
                </div>
                <div class="top-right">
                    <a href="#" class="btn-download">Download Live 18 India APP</a>
                    <a href="#" class="btn-watch-live">Watch LIVE</a>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="main-header">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <?php if (!empty($settings['logo'])): ?>
                            <img src="uploads/<?php echo $settings['logo']; ?>" alt="Logo">
                        <?php else: ?>
                            <div class="logo-text">
                                <span class="logo-news">LIVE</span><span class="logo-number">18</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <nav class="main-nav">
                        <a href="index.php">Home</a>
                        <a href="latest.php">Latest</a>
                        <?php 
                        // Show only categories from admin panel
                        foreach ($categories as $cat): 
                        ?>
                            <a href="category.php?slug=<?php echo $cat['slug']; ?>"><?php echo $cat['name']; ?></a>
                        <?php endforeach; ?>
                        <a href="videos.php">Videos</a>
                        <a href="contact.php">Contact</a>
                        <a href="about.php">About</a>
                        <?php if (!empty($settings['live_video_url'])): ?>
                            <a href="#" class="notification-icon" onclick="openLiveVideo(event)">🔔</a>
                        <?php endif; ?>
                    </nav>
                    <div class="header-actions">
                        <button class="search-btn">🔍 Ask Live 18 India</button>
                        <a href="admin/login.php" class="signin-btn">Sign in</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trending Bar -->
        <div class="trending-bar">
            <div class="container">
                <div class="trending-content">
                    <span class="trending-label">⚡ TRENDING:</span>
                    <div class="trending-items">
                        <a href="#">India News</a>
                        <a href="#">World Updates</a>
                        <a href="#">Sports</a>
                        <a href="#">Entertainment</a>
                    </div>
                    <div class="social-follow">
                        <span>Follow Us</span>
                        <a href="#" class="social-icon whatsapp">📱</a>
                        <a href="#" class="social-icon facebook">f</a>
                        <a href="#" class="social-icon twitter">𝕏</a>
                        <a href="#" class="social-icon youtube">▶</a>
                        <a href="#" class="social-icon instagram">📷</a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($settings['breaking_news'])): ?>
            <div class="breaking-news">
                <div class="container">
                    <span class="breaking-label">BREAKING</span>
                    <div class="breaking-text">
                        <marquee behavior="scroll" direction="left" scrollamount="5"><?php echo $settings['breaking_news']; ?></marquee>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </header>

    <!-- Live Video Popup -->
    <?php if (!empty($settings['live_video_url'])): ?>
    <div id="liveVideoModal" class="video-modal">
        <div class="video-modal-content">
            <span class="video-close" onclick="closeLiveVideo()">&times;</span>
            <h3>🔴 LIVE NOW</h3>
            <div class="video-wrapper">
                <?php if (strpos($settings['live_video_url'], 'youtube.com') !== false || strpos($settings['live_video_url'], 'youtu.be') !== false): 
                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $settings['live_video_url'], $matches);
                    $youtube_id = $matches[1] ?? '';
                ?>
                    <iframe src="https://www.youtube.com/embed/<?php echo $youtube_id; ?>?autoplay=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                <?php else: ?>
                    <video controls autoplay>
                        <source src="uploads/<?php echo $settings['live_video_url']; ?>" type="video/mp4">
                    </video>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    function openLiveVideo(e) {
        e.preventDefault();
        document.getElementById('liveVideoModal').style.display = 'flex';
    }
    
    function closeLiveVideo() {
        document.getElementById('liveVideoModal').style.display = 'none';
    }
    
    window.onclick = function(event) {
        const modal = document.getElementById('liveVideoModal');
        if (event.target == modal) {
            closeLiveVideo();
        }
    }
    </script>
    <?php endif; ?>
