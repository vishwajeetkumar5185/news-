<?php 
include 'includes/header.php';

$id = $_GET['id'] ?? 0;
$news = $conn->query("SELECT n.*, c.name as category_name FROM news n LEFT JOIN categories c ON n.category_id = c.id WHERE n.id = $id")->fetch_assoc();

if (!$news) {
    header('Location: index.php');
    exit;
}
?>

<div class="container single-news">
    <article>
        <h1><?php echo $news['title']; ?></h1>
        <div class="meta">
            <span>Category: <?php echo $news['category_name']; ?></span>
            <span>Date: <?php echo date('d M Y', strtotime($news['created_at'])); ?></span>
        </div>
        <img src="uploads/<?php echo $news['image']; ?>" alt="<?php echo $news['title']; ?>">
        <div class="content">
            <?php echo $news['content']; ?>
        </div>
    </article>

    <aside class="sidebar">
        <?php $banners = getBanners($conn, 'sidebar'); ?>
        <?php foreach ($banners as $banner): ?>
            <div class="banner">
                <a href="<?php echo $banner['link']; ?>">
                    <img src="uploads/<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>">
                </a>
            </div>
        <?php endforeach; ?>

        <h3>Related News</h3>
        <?php
        $related = $conn->query("SELECT * FROM news WHERE category_id = {$news['category_id']} AND id != $id LIMIT 5");
        while ($rel = $related->fetch_assoc()):
        ?>
            <div class="related-item">
                <a href="single.php?id=<?php echo $rel['id']; ?>"><?php echo $rel['title']; ?></a>
            </div>
        <?php endwhile; ?>
    </aside>
</div>

<?php include 'includes/footer.php'; ?>
