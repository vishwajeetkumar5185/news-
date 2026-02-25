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
    
    <!-- Dynamic Title -->
    <title><?php echo $settings['site_name'] ?? 'Live 18 India - Breaking News, Latest Updates 24x7'; ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Live 18 India - Your trusted source for breaking news, latest updates, and live coverage 24x7. Get India news, world news, sports, entertainment, business updates and more.">
    <meta name="keywords" content="Live 18 India, India news, breaking news, latest news, live news 24x7, Hindi news, English news, sports news, entertainment news, business news, world news, politics, technology">
    <meta name="author" content="Live 18 India">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Hindi, English">
    <meta name="revisit-after" content="1 days">
    <meta name="distribution" content="global">
    <meta name="rating" content="general">
    
    <!-- Open Graph Meta Tags (Facebook, LinkedIn) -->
    <meta property="og:site_name" content="Live 18 India">
    <meta property="og:title" content="Live 18 India - Breaking News & Latest Updates 24x7">
    <meta property="og:description" content="Your trusted source for breaking news, latest updates, and live coverage 24x7. India's leading news channel.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:image" content="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/uploads/' . ($settings['logo'] ?? 'logo.png'); ?>">
    <meta property="og:locale" content="hi_IN">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@Live18India">
    <meta name="twitter:title" content="Live 18 India - Breaking News & Latest Updates 24x7">
    <meta name="twitter:description" content="Your trusted source for breaking news, latest updates, and live coverage 24x7. India's leading news channel.">
    <meta name="twitter:image" content="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/uploads/' . ($settings['logo'] ?? 'logo.png'); ?>">
    
    <!-- Mobile App Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Live 18 India">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#e53935">
    
    <!-- Geo Tags -->
    <meta name="geo.region" content="IN">
    <meta name="geo.placename" content="India">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Favicon -->
    <?php if (!empty($settings['logo'])): ?>
        <link rel="icon" type="image/x-icon" href="uploads/<?php echo $settings['logo']; ?>">
        <link rel="shortcut icon" type="image/x-icon" href="uploads/<?php echo $settings['logo']; ?>">
        <link rel="apple-touch-icon" href="uploads/<?php echo $settings['logo']; ?>">
    <?php else: ?>
        <!-- SVG Favicon for LIVE18 -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23000'/><text x='10' y='35' font-family='Arial' font-size='20' font-weight='900' fill='%23fff'>LIVE</text><rect x='0' y='50' width='100' height='50' fill='%23e53935'/><text x='25' y='85' font-family='Arial' font-size='35' font-weight='900' fill='%23fff'>18</text></svg>">
    <?php endif; ?>
    
    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://www.youtube.com">
    
    <link rel="stylesheet" href="assets/style.css">
    
    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NewsMediaOrganization",
        "name": "Live 18 India",
        "alternateName": "Live18 India News",
        "url": "<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>",
        "logo": "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/uploads/' . ($settings['logo'] ?? 'logo.png'); ?>",
        "description": "India's trusted news channel providing breaking news, latest updates, and live coverage 24x7",
        "sameAs": [
            "https://www.facebook.com/Live18India",
            "https://twitter.com/Live18India",
            "https://www.youtube.com/Live18India",
            "https://www.instagram.com/Live18India"
        ],
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+91-XXXXXXXXXX",
            "contactType": "Customer Service",
            "areaServed": "IN",
            "availableLanguage": ["Hindi", "English"]
        }
    }
    </script>
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
                        <a href="index.php" class="logo-link">
                            <?php if (!empty($settings['logo'])): ?>
                                <img src="uploads/<?php echo $settings['logo']; ?>" alt="Logo">
                            <?php else: ?>
                                <div class="logo-text">
                                    <span class="logo-news">LIVE</span><span class="logo-number">18</span>
                                </div>
                            <?php endif; ?>
                        </a>
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
                        <button class="search-btn" onclick="window.location.href='contact.php'">
                            <span class="search-icon">🔍</span>
                            <span class="search-text">Ask Live 18 India</span>
                        </button>
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
