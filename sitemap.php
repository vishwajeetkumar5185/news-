<?php
require_once 'config/database.php';
require_once 'config/functions.php';

// SEO Meta Data
$pageTitle = "Sitemap - Live 18 India | Complete Site Navigation";
$pageDescription = "Complete sitemap of Live 18 India - Find all news articles, categories, videos, and pages. Navigate easily through India's trusted news portal.";
$pageKeywords = "Live 18 India sitemap, news navigation, site map, news categories, latest articles, India news portal";
$canonicalUrl = "https://live18india.com/sitemap.php";

// Open Graph Meta Tags
$ogTitle = "Complete Sitemap - Live 18 India";
$ogDescription = "Navigate through all pages and content on Live 18 India - India's most trusted news portal";
$ogImage = "https://live18india.com/assets/images/live18-sitemap.jpg";
$ogUrl = $canonicalUrl;

// Twitter Card Meta Tags
$twitterTitle = $ogTitle;
$twitterDescription = $ogDescription;
$twitterImage = $ogImage;

// Schema.org JSON-LD
$schemaData = [
    "@context" => "https://schema.org",
    "@type" => "WebPage",
    "name" => $pageTitle,
    "description" => $pageDescription,
    "url" => $canonicalUrl,
    "mainEntity" => [
        "@type" => "SiteNavigationElement",
        "name" => "Live 18 India Sitemap",
        "description" => "Complete navigation structure of Live 18 India news portal"
    ],
    "publisher" => [
        "@type" => "NewsMediaOrganization",
        "name" => "Live 18 India",
        "url" => "https://live18india.com",
        "logo" => [
            "@type" => "ImageObject",
            "url" => "https://live18india.com/assets/images/logo.png"
        ]
    ],
    "dateModified" => date('c'),
    "inLanguage" => "en-IN"
];

// Set page category for analytics
$pageCategory = 'sitemap_page';

include 'includes/header.php';

// Fetch categories
$categories = [];
$cat_query = "SELECT * FROM categories ORDER BY name ASC";
$cat_result = $conn->query($cat_query);
if ($cat_result) {
    while ($row = $cat_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Fetch recent news
$recent_news = [];
$news_query = "SELECT id, title, created_at FROM news ORDER BY created_at DESC LIMIT 20";
$news_result = $conn->query($news_query);
if ($news_result) {
    while ($row = $news_result->fetch_assoc()) {
        $recent_news[] = $row;
    }
}
?>

<!-- Schema.org JSON-LD -->
<script type="application/ld+json">
<?php echo json_encode($schemaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
</script>

<div class="sitemap-page">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <ol itemscope itemtype="https://schema.org/BreadcrumbList">
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="index.php">
                        <span itemprop="name">Home</span>
                    </a>
                    <meta itemprop="position" content="1" />
                </li>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span itemprop="name">Sitemap</span>
                    <meta itemprop="position" content="2" />
                </li>
            </ol>
        </nav>

        <div class="sitemap-header">
            <h1>🗺️ Complete Sitemap</h1>
            <p class="sitemap-description">Navigate through all pages and content on Live 18 India - India's most trusted news portal. Find news articles, categories, videos, and important pages easily.</p>
            
            <!-- Quick Stats -->
            <div class="sitemap-stats">
                <div class="stat-item">
                    <span class="stat-number"><?php echo count($categories); ?></span>
                    <span class="stat-label">Categories</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo count($recent_news); ?></span>
                    <span class="stat-label">Recent Articles</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">10+</span>
                    <span class="stat-label">Main Pages</span>
                </div>
            </div>
        </div>

        <div class="sitemap-content">
            <!-- XML Sitemap Link -->
            <div class="xml-sitemap-notice">
                <h2>🤖 For Search Engines</h2>
                <p>Looking for XML sitemap? <a href="sitemap.xml" target="_blank">Click here for XML Sitemap</a></p>
            </div>

            <div class="sitemap-grid">
                <!-- Main Pages -->
                <div class="sitemap-section" itemscope itemtype="https://schema.org/SiteNavigationElement">
                    <h2>📄 Main Pages</h2>
                    <ul class="sitemap-links">
                        <li><a href="index.php" title="Live 18 India Homepage - Latest News">🏠 Home</a></li>
                        <li><a href="latest.php" title="Latest Breaking News from India">📰 Latest News</a></li>
                        <li><a href="videos.php" title="News Videos and Live Coverage">🎥 Videos</a></li>
                        <li><a href="about.php" title="About Live 18 India News Portal">ℹ️ About Us</a></li>
                        <li><a href="contact.php" title="Contact Live 18 India Team">📞 Contact Us</a></li>
                        <li><a href="search.php" title="Search News Articles">🔍 Search</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div class="sitemap-section" itemscope itemtype="https://schema.org/SiteNavigationElement">
                    <h2>📂 News Categories</h2>
                    <ul class="sitemap-links">
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <li>
                                    <a href="category.php?id=<?php echo $category['id']; ?>" 
                                       title="<?php echo htmlspecialchars($category['name']); ?> News - Live 18 India">
                                        📋 <?php echo htmlspecialchars($category['name']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>No categories available</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Recent News -->
                <div class="sitemap-section" itemscope itemtype="https://schema.org/ItemList">
                    <h2>📈 Recent News Articles</h2>
                    <ul class="sitemap-links">
                        <?php if (!empty($recent_news)): ?>
                            <?php foreach ($recent_news as $index => $news): ?>
                                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                    <meta itemprop="position" content="<?php echo $index + 1; ?>" />
                                    <a href="single.php?id=<?php echo $news['id']; ?>" 
                                       itemprop="item"
                                       title="<?php echo htmlspecialchars($news['title']); ?> - Live 18 India">
                                        <span itemprop="name">📄 <?php echo htmlspecialchars($news['title']); ?></span>
                                    </a>
                                    <span class="sitemap-date"><?php echo date('M d, Y', strtotime($news['created_at'])); ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>No recent news available</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Legal Pages -->
                <div class="sitemap-section" itemscope itemtype="https://schema.org/SiteNavigationElement">
                    <h2>⚖️ Legal & Policies</h2>
                    <ul class="sitemap-links">
                        <li><a href="privacy-policy.php" title="Privacy Policy - Live 18 India">🔒 Privacy Policy</a></li>
                        <li><a href="terms-of-use.php" title="Terms of Use - Live 18 India">📋 Terms of Use</a></li>
                        <li><a href="cookie-policy.php" title="Cookie Policy - Live 18 India">🍪 Cookie Policy</a></li>
                        <li><a href="sitemap.php" title="Complete Sitemap - Live 18 India">🗺️ Sitemap</a></li>
                    </ul>
                </div>

                <!-- Social Media -->
                <div class="sitemap-section" itemscope itemtype="https://schema.org/SiteNavigationElement">
                    <h2>🌐 Connect With Us</h2>
                    <ul class="sitemap-links">
                        <li><a href="#" target="_blank" rel="noopener" title="Follow Live 18 India on Facebook">📘 Facebook</a></li>
                        <li><a href="#" target="_blank" rel="noopener" title="Follow Live 18 India on Twitter">🐦 Twitter / X</a></li>
                        <li><a href="#" target="_blank" rel="noopener" title="Follow Live 18 India on Instagram">📷 Instagram</a></li>
                        <li><a href="#" target="_blank" rel="noopener" title="Subscribe to Live 18 India YouTube">📺 YouTube</a></li>
                        <li><a href="#" target="_blank" rel="noopener" title="Join Live 18 India WhatsApp">📱 WhatsApp</a></li>
                    </ul>
                </div>

                <!-- Additional Resources -->
                <div class="sitemap-section" itemscope itemtype="https://schema.org/SiteNavigationElement">
                    <h2>🔗 Resources</h2>
                    <ul class="sitemap-links">
                        <li><a href="#" title="Career Opportunities at Live 18 India">💼 Careers</a></li>
                        <li><a href="#" title="Advertise with Live 18 India">📢 Advertise With Us</a></li>
                        <li><a href="#" title="RSS Feeds - Live 18 India">📡 RSS Feeds</a></li>
                        <li><a href="#" title="Subscribe to Newsletter">📧 Newsletter</a></li>
                        <li><a href="sitemap.xml" title="XML Sitemap for Search Engines">🤖 XML Sitemap</a></li>
                    </ul>
                </div>
            </div>

            <div class="sitemap-footer-note">
                <p><strong>Note:</strong> This sitemap is updated regularly. For the most current content, please visit our <a href="index.php">homepage</a>.</p>
                <p>Last Updated: <?php echo date('F d, Y'); ?></p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
