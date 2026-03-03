<?php 
require_once 'config/database.php';
require_once 'config/functions.php';
$conn = getConnection();

$id = $_GET['id'] ?? 0;
$news = $conn->query("SELECT n.*, c.name as category_name FROM news n LEFT JOIN categories c ON n.category_id = c.id WHERE n.id = $id")->fetch_assoc();

if (!$news) {
    header('Location: index.php');
    exit;
}

// SEO Meta Data for News Article
$pageTitle = "{$news['title']} | Live 18 India";
$pageDescription = !empty($news['subtitle']) ? $news['subtitle'] : substr(strip_tags($news['content']), 0, 160) . "...";
$pageKeywords = "{$news['category_name']}, {$news['title']}, Live 18 India, news, breaking news, " . str_replace(' ', ', ', $news['title']);
$canonicalUrl = "https://live18india.com/single.php?id={$news['id']}";

// Open Graph Meta Tags for Article
$ogTitle = $news['title'];
$ogDescription = $pageDescription;
$ogImage = "https://live18india.com/uploads/{$news['image']}";
$ogUrl = $canonicalUrl;
$ogType = "article";

// Twitter Card Meta Tags
$twitterTitle = $ogTitle;
$twitterDescription = $ogDescription;
$twitterImage = $ogImage;

// Article-specific meta tags
$articlePublishedTime = date('c', strtotime($news['created_at']));
$articleModifiedTime = date('c', strtotime($news['created_at']));
$articleAuthor = "Live 18 India";
$articleSection = $news['category_name'];
$articleTags = $news['category_name'] . ", news, breaking news";

// Schema.org JSON-LD for News Article
$schemaData = [
    "@context" => "https://schema.org",
    "@type" => "NewsArticle",
    "headline" => $news['title'],
    "description" => $pageDescription,
    "image" => [
        "@type" => "ImageObject",
        "url" => $ogImage,
        "width" => 1200,
        "height" => 630
    ],
    "datePublished" => $articlePublishedTime,
    "dateModified" => $articleModifiedTime,
    "author" => [
        "@type" => "Organization",
        "name" => $articleAuthor,
        "url" => "https://live18india.com"
    ],
    "publisher" => [
        "@type" => "NewsMediaOrganization",
        "name" => "Live 18 India",
        "url" => "https://live18india.com",
        "logo" => [
            "@type" => "ImageObject",
            "url" => "https://live18india.com/assets/images/logo.png",
            "width" => 200,
            "height" => 60
        ]
    ],
    "mainEntityOfPage" => [
        "@type" => "WebPage",
        "@id" => $canonicalUrl
    ],
    "articleSection" => $articleSection,
    "keywords" => $articleTags,
    "url" => $canonicalUrl,
    "breadcrumb" => [
        "@type" => "BreadcrumbList",
        "itemListElement" => [
            [
                "@type" => "ListItem",
                "position" => 1,
                "name" => "Home",
                "item" => "https://live18india.com"
            ],
            [
                "@type" => "ListItem",
                "position" => 2,
                "name" => $news['category_name'],
                "item" => "https://live18india.com/category.php?id={$news['category_id']}"
            ],
            [
                "@type" => "ListItem",
                "position" => 3,
                "name" => $news['title']
            ]
        ]
    ]
];

include 'includes/header.php';
// Set page category for analytics
$pageCategory = 'news_article';
?>

<!-- Schema.org JSON-LD for News Article -->
<script type="application/ld+json">
<?php echo json_encode($schemaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
</script>

<!-- Track article view -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Track news article view
    trackNewsView(
        '<?php echo $news['id']; ?>', 
        '<?php echo htmlspecialchars($news['category_name']); ?>', 
        '<?php echo htmlspecialchars($news['title']); ?>'
    );
    
    // Track reading time
    let startTime = Date.now();
    window.addEventListener('beforeunload', function() {
        let readingTime = Math.round((Date.now() - startTime) / 1000);
        gtag('event', 'reading_time', {
            'article_id': '<?php echo $news['id']; ?>',
            'reading_seconds': readingTime,
            'event_category': 'Engagement',
            'event_label': 'Article Reading Time'
        });
    });
    
    // Track social sharing clicks
    document.querySelectorAll('.social-share a').forEach(function(link) {
        link.addEventListener('click', function(e) {
            let platform = this.className.includes('facebook') ? 'Facebook' : 
                          this.className.includes('twitter') ? 'Twitter' : 
                          this.className.includes('whatsapp') ? 'WhatsApp' : 'Other';
            
            trackSocialShare(
                platform, 
                window.location.href, 
                '<?php echo htmlspecialchars($news['title']); ?>'
            );
        });
    });
});
</script>

<!-- Hero Section with Full-Width Image -->
<div class="single-hero">
    <div class="hero-image">
        <img src="uploads/<?php echo $news['image']; ?>" alt="<?php echo $news['title']; ?>">
        <div class="hero-overlay">
            <div class="container">
                <div class="hero-content">
                    <span class="hero-category"><?php echo $news['category_name']; ?></span>
                    <h1 class="hero-title"><?php echo $news['title']; ?></h1>
                    <div class="hero-meta">
                        <span class="hero-date"><?php echo date('d M Y', strtotime($news['created_at'])); ?></span>
                        <span class="hero-reading-time">5 min read</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Article Content -->
<div class="single-article-wrapper">
    <div class="container">
        <div class="single-layout">
            <main class="article-main">
                <div class="article-content">
                    <?php echo $news['content']; ?>
                </div>
                
                <!-- Social Share -->
                <div class="social-share">
                    <h4>Share this article</h4>
                    <div class="share-buttons">
                        <a href="#" class="share-btn facebook">Facebook</a>
                        <a href="#" class="share-btn twitter">Twitter</a>
                        <a href="#" class="share-btn whatsapp">WhatsApp</a>
                        <a href="#" class="share-btn copy">Copy Link</a>
                    </div>
                </div>
            </main>

            <aside class="article-sidebar">
                <!-- Banners -->
                <?php $banners = getBanners($conn, 'sidebar'); ?>
                <?php foreach ($banners as $banner): ?>
                    <div class="sidebar-banner-single">
                        <a href="<?php echo $banner['link']; ?>">
                            <img src="uploads/<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>">
                        </a>
                    </div>
                <?php endforeach; ?>

                <!-- Related News -->
                <div class="related-news-widget">
                    <h3 class="widget-title">Related Articles</h3>
                    <?php
                    $related = $conn->query("SELECT * FROM news WHERE category_id = {$news['category_id']} AND id != $id LIMIT 4");
                    while ($rel = $related->fetch_assoc()):
                    ?>
                        <div class="related-article">
                            <a href="single.php?id=<?php echo $rel['id']; ?>" class="related-link">
                                <div class="related-content">
                                    <h4><?php echo $rel['title']; ?></h4>
                                    <span class="related-date"><?php echo date('d M Y', strtotime($rel['created_at'])); ?></span>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
