<?php 
include 'includes/header.php';

$id = $_GET['id'] ?? 0;
$news = $conn->query("SELECT n.*, c.name as category_name FROM news n LEFT JOIN categories c ON n.category_id = c.id WHERE n.id = $id")->fetch_assoc();

if (!$news) {
    header('Location: index.php');
    exit;
}
?>

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
