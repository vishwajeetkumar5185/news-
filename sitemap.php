<?php
require_once 'config/database.php';
require_once 'config/functions.php';

$pageTitle = "Sitemap - Live 18 India";
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

<div class="sitemap-page">
    <div class="container">
        <div class="sitemap-header">
            <h1>Sitemap</h1>
            <p>Find all pages and content on Live 18 India</p>
        </div>

        <div class="sitemap-content">
            <div class="sitemap-grid">
                <!-- Main Pages -->
                <div class="sitemap-section">
                    <h2>Main Pages</h2>
                    <ul class="sitemap-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="latest.php">Latest News</a></li>
                        <li><a href="videos.php">Videos</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li><a href="search.php">Search</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div class="sitemap-section">
                    <h2>News Categories</h2>
                    <ul class="sitemap-links">
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <li>
                                    <a href="category.php?id=<?php echo $category['id']; ?>">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>No categories available</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Recent News -->
                <div class="sitemap-section">
                    <h2>Recent News Articles</h2>
                    <ul class="sitemap-links">
                        <?php if (!empty($recent_news)): ?>
                            <?php foreach ($recent_news as $news): ?>
                                <li>
                                    <a href="single.php?id=<?php echo $news['id']; ?>">
                                        <?php echo htmlspecialchars($news['title']); ?>
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
                <div class="sitemap-section">
                    <h2>Legal & Policies</h2>
                    <ul class="sitemap-links">
                        <li><a href="privacy-policy.php">Privacy Policy</a></li>
                        <li><a href="terms-of-use.php">Terms of Use</a></li>
                        <li><a href="cookie-policy.php">Cookie Policy</a></li>
                        <li><a href="sitemap.php">Sitemap</a></li>
                    </ul>
                </div>

                <!-- Social Media -->
                <div class="sitemap-section">
                    <h2>Connect With Us</h2>
                    <ul class="sitemap-links">
                        <li><a href="#" target="_blank">Facebook</a></li>
                        <li><a href="#" target="_blank">Twitter</a></li>
                        <li><a href="#" target="_blank">Instagram</a></li>
                        <li><a href="#" target="_blank">YouTube</a></li>
                        <li><a href="#" target="_blank">WhatsApp</a></li>
                    </ul>
                </div>

                <!-- Additional Resources -->
                <div class="sitemap-section">
                    <h2>Resources</h2>
                    <ul class="sitemap-links">
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Advertise With Us</a></li>
                        <li><a href="#">RSS Feeds</a></li>
                        <li><a href="#">Newsletter</a></li>
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
