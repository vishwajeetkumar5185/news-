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
    <!-- Google Search Console Verification -->
    <meta name="google-site-verification" content="fYqxgsFo-6ihd8B1achRBYZKptail6r1ubui6OKCmDo" />
    
    <!-- Google AdSense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5062126875706614"
     crossorigin="anonymous"></script>
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-G14C56RN9E"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        
        gtag('config', 'G-G14C56RN9E', {
            // Enhanced tracking options
            'send_page_view': true,
            'anonymize_ip': true,
            'allow_google_signals': true,
            'allow_ad_personalization_signals': true
        });
        
        // Custom events for news portal
        function trackNewsView(articleId, category, title) {
            gtag('event', 'news_article_view', {
                'article_id': articleId,
                'article_category': category,
                'article_title': title,
                'event_category': 'News',
                'event_label': 'Article View'
            });
        }
        
        function trackVideoPlay(videoId, videoTitle) {
            gtag('event', 'video_play', {
                'video_id': videoId,
                'video_title': videoTitle,
                'event_category': 'Video',
                'event_label': 'Video Play'
            });
        }
        
        function trackSearch(searchTerm, resultsCount) {
            gtag('event', 'search', {
                'search_term': searchTerm,
                'results_count': resultsCount,
                'event_category': 'Search',
                'event_label': 'Site Search'
            });
        }
        
        function trackSocialShare(platform, url, title) {
            gtag('event', 'share', {
                'method': platform,
                'content_type': 'article',
                'item_id': url,
                'content_title': title,
                'event_category': 'Social',
                'event_label': 'Share Article'
            });
        }
        
        function trackContactForm(formType) {
            gtag('event', 'form_submit', {
                'form_type': formType,
                'event_category': 'Contact',
                'event_label': 'Form Submission'
            });
        }
        
        // Track page category for better segmentation
        <?php if (isset($pageCategory)): ?>
        gtag('config', 'G-G14C56RN9E', {
            'custom_map': {'custom_parameter_1': 'page_category'}
        });
        gtag('event', 'page_view', {
            'custom_parameter_1': '<?php echo $pageCategory; ?>'
        });
        <?php endif; ?>
    </script>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Dynamic Title -->
    <title><?php echo isset($pageTitle) ? $pageTitle : 'live18 इंडिया LIVE TV - Latest India News, Breaking News in Hindi'; ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Live 18 India LIVE TV Streaming: Latest India News, Breaking News in Hindi, आज की ताज़ा ख़बरें, हिंदी न्यूज़ लाइव, देश-दुनिया समाचार, राजनीति, खेल, मनोरंजन — देखें Live 18 India लाइव।'; ?>">
    <meta name="keywords" content="<?php echo isset($pageKeywords) ? $pageKeywords : 'live18 india, live tv, hindi news, breaking news, india news, latest news, ताज़ा खबरें, हिंदी न्यूज़, लाइव टीवी, देश समाचार, दुनिया समाचार, राजनीति, खेल, मनोरंजन, live streaming'; ?>">
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
    <link rel="icon" type="image/jpeg" href="uploads/icon.jpeg">
    <link rel="shortcut icon" type="image/jpeg" href="uploads/icon.jpeg">
    <link rel="apple-touch-icon" href="uploads/icon.jpeg">
    <link rel="apple-touch-icon" sizes="180x180" href="uploads/icon.jpeg">
    <link rel="icon" type="image/jpeg" sizes="32x32" href="uploads/icon.jpeg">
    <link rel="icon" type="image/jpeg" sizes="16x16" href="uploads/icon.jpeg">
    
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
                        <?php if (!empty($settings['live_video_url']) && ($settings['live_status'] ?? 0) == 1): ?>
                            <a href="#" class="notification-icon live-indicator-btn" onclick="openLiveVideo(event)">🔴 LIVE</a>
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
    <?php if (!empty($settings['live_video_url']) && ($settings['live_status'] ?? 0) == 1): ?>
    <div id="liveVideoModal" class="video-modal">
        <div class="video-modal-content">
            <span class="video-close" onclick="closeLiveVideo()">&times;</span>
            <h3>🔴 LIVE NOW - Live 18 India</h3>
            <div class="video-wrapper">
                <?php if (strpos($settings['live_video_url'], 'youtube.com') !== false || strpos($settings['live_video_url'], 'youtu.be') !== false): 
                    // Multiple patterns for different YouTube URL formats
                    $youtube_id = '';
                    $is_live_url = false;
                    
                    // Pattern 1: youtube.com/watch?v=VIDEO_ID
                    if (preg_match('/(?:youtube\.com\/watch\?v=)([a-zA-Z0-9_-]{11})/', $settings['live_video_url'], $matches)) {
                        $youtube_id = $matches[1];
                    }
                    // Pattern 2: youtu.be/VIDEO_ID
                    elseif (preg_match('/(?:youtu\.be\/)([a-zA-Z0-9_-]{11})/', $settings['live_video_url'], $matches)) {
                        $youtube_id = $matches[1];
                    }
                    // Pattern 3: youtube.com/embed/VIDEO_ID
                    elseif (preg_match('/(?:youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $settings['live_video_url'], $matches)) {
                        $youtube_id = $matches[1];
                    }
                    // Pattern 4: youtube.com/v/VIDEO_ID
                    elseif (preg_match('/(?:youtube\.com\/v\/)([a-zA-Z0-9_-]{11})/', $settings['live_video_url'], $matches)) {
                        $youtube_id = $matches[1];
                    }
                    // Pattern 5: youtube.com/live/VIDEO_ID (for live streams)
                    elseif (preg_match('/(?:youtube\.com\/live\/)([a-zA-Z0-9_-]{11})/', $settings['live_video_url'], $matches)) {
                        $youtube_id = $matches[1];
                        $is_live_url = true;
                    }
                    
                    if ($youtube_id): ?>
                        <div style="position: relative;">
                            <iframe 
                                id="youtube-iframe"
                                src="https://www.youtube.com/embed/<?php echo $youtube_id; ?>?autoplay=0&mute=0&rel=0&modestbranding=1" 
                                width="600" 
                                height="338" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen
                                style="border-radius: 8px;">
                            </iframe>
                            
                            <!-- Fallback message for unavailable videos -->
                            <div id="video-fallback" style="position: absolute; top: 0; left: 0; width: 600px; height: 338px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: none; align-items: center; justify-content: center; border-radius: 8px; color: white; z-index: 10;">
                                <div style="text-align: center; padding: 30px;">
                                    <div style="font-size: 36px; margin-bottom: 15px;">📺</div>
                                    <h3 style="margin-bottom: 12px; font-size: 20px;">Live Stream Currently Offline</h3>
                                    <p style="margin-bottom: 15px; opacity: 0.9; font-size: 14px;">The live stream is not available right now.</p>
                                    <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 12px;">
                                        <strong>Video ID:</strong> <?php echo $youtube_id; ?><br>
                                        <?php if ($is_live_url): ?>
                                            <strong>Type:</strong> 🔴 Live Stream URL<br>
                                        <?php endif; ?>
                                        <strong>Status:</strong> Stream may have ended or not started yet
                                    </div>
                                    <button onclick="location.reload()" style="background: #fff; color: #333; padding: 8px 16px; border: none; border-radius: 15px; cursor: pointer; font-weight: 600; font-size: 12px;">
                                        🔄 Refresh Page
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                        // Simple approach - let YouTube iframe load normally
                        // Don't automatically show fallback unless there's a real error
                        function checkVideoAvailability() {
                            // Do nothing - let YouTube handle video availability
                            // The iframe will show YouTube's own error message if video is unavailable
                        }
                        </script>
                    <?php else: ?>
                        <div style="width: 600px; height: 338px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                            <div style="text-align: center; color: #666;">
                                <h3>❌ Invalid YouTube URL</h3>
                                <p>Please check the YouTube URL in admin settings</p>
                                <small>URL: <?php echo htmlspecialchars($settings['live_video_url']); ?></small>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <video controls width="600" height="338" style="border-radius: 8px;">
                        <source src="uploads/<?php echo $settings['live_video_url']; ?>" type="video/mp4">
                        <div style="width: 600px; height: 338px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                            <p>Your browser does not support the video tag.</p>
                        </div>
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
