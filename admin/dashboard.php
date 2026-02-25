<?php 
require 'includes/auth.php';
include 'includes/header.php';

$totalBanners = $conn->query("SELECT COUNT(*) as count FROM banners")->fetch_assoc()['count'];
$totalCategories = $conn->query("SELECT COUNT(*) as count FROM categories")->fetch_assoc()['count'];
$totalNews = $conn->query("SELECT COUNT(*) as count FROM news")->fetch_assoc()['count'];
$totalMessages = $conn->query("SELECT COUNT(*) as count FROM contacts")->fetch_assoc()['count'];
?>

<h1>Dashboard</h1>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Banners</h3>
        <p class="stat-number"><?php echo $totalBanners; ?></p>
    </div>
    <div class="stat-card">
        <h3>Total Categories</h3>
        <p class="stat-number"><?php echo $totalCategories; ?></p>
    </div>
    <div class="stat-card">
        <h3>Total News</h3>
        <p class="stat-number"><?php echo $totalNews; ?></p>
    </div>
    <div class="stat-card">
        <h3>Messages</h3>
        <p class="stat-number"><?php echo $totalMessages; ?></p>
    </div>
</div>

<h2>Latest News</h2>
<table>
    <tr>
        <th>Title</th>
        <th>Category</th>
        <th>Date</th>
    </tr>
    <?php
    $latest = $conn->query("SELECT n.*, c.name as category FROM news n LEFT JOIN categories c ON n.category_id = c.id ORDER BY n.created_at DESC LIMIT 10");
    while ($row = $latest->fetch_assoc()):
    ?>
    <tr>
        <td><?php echo $row['title']; ?></td>
        <td><?php echo $row['category']; ?></td>
        <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include 'includes/footer.php'; ?>
