<?php 
require 'includes/auth.php';
include 'includes/header.php';

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = clean($_POST['title']);
    $subtitle = clean($_POST['subtitle']);
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $featured = $_POST['featured'] ?? 0;
    $youtube_url = clean($_POST['youtube_url']);
    
    $image = '';
    $video_file = '';
    
    if ($_FILES['image']['name']) {
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image);
    }
    
    if ($_FILES['video_file']['name']) {
        $video_file = time() . '_' . $_FILES['video_file']['name'];
        move_uploaded_file($_FILES['video_file']['tmp_name'], '../uploads/' . $video_file);
    }
    
    if (isset($_POST['id']) && $_POST['id']) {
        $id = $_POST['id'];
        $sql = "UPDATE news SET title='$title', subtitle='$subtitle', content='$content', category_id=$category_id, featured=$featured, youtube_url='$youtube_url'";
        if ($image) $sql .= ", image='$image'";
        if ($video_file) $sql .= ", video_file='$video_file'";
        $sql .= " WHERE id=$id";
        $conn->query($sql);
    } else {
        $conn->query("INSERT INTO news (title, subtitle, content, image, video_file, youtube_url, category_id, featured) VALUES ('$title', '$subtitle', '$content', '$image', '$video_file', '$youtube_url', $category_id, $featured)");
    }
    header('Location: news.php');
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM news WHERE id=" . $_GET['delete']);
    header('Location: news.php');
    exit;
}

$editNews = null;
if (isset($_GET['edit'])) {
    $editNews = $conn->query("SELECT * FROM news WHERE id=" . $_GET['edit'])->fetch_assoc();
}
?>

<h1>Manage News</h1>

<form method="POST" enctype="multipart/form-data" class="admin-form">
    <input type="hidden" name="id" value="<?php echo $editNews['id'] ?? ''; ?>">
    
    <label>Title (Heading)</label>
    <input type="text" name="title" placeholder="News Title" value="<?php echo $editNews['title'] ?? ''; ?>" required>
    
    <label>Subtitle (Sub Heading)</label>
    <input type="text" name="subtitle" placeholder="News Subtitle" value="<?php echo $editNews['subtitle'] ?? ''; ?>">
    
    <label>Content</label>
    <textarea name="content" placeholder="News Content" rows="6" required><?php echo $editNews['content'] ?? ''; ?></textarea>
    
    <label>Upload Image</label>
    <input type="file" name="image" accept="image/*" <?php echo $editNews ? '' : ''; ?>>
    <?php if (isset($editNews['image']) && $editNews['image']): ?>
        <img src="../uploads/<?php echo $editNews['image']; ?>" width="100">
    <?php endif; ?>
    
    <label>Upload Video (optional)</label>
    <input type="file" name="video_file" accept="video/*">
    
    <label>YouTube URL (optional)</label>
    <input type="text" name="youtube_url" placeholder="https://youtube.com/watch?v=..." value="<?php echo $editNews['youtube_url'] ?? ''; ?>">
    
    <label>Category</label>
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php
        $cats = $conn->query("SELECT * FROM categories");
        while ($cat = $cats->fetch_assoc()):
        ?>
            <option value="<?php echo $cat['id']; ?>" <?php echo ($editNews['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                <?php echo $cat['name']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    
    <label><input type="checkbox" name="featured" value="1" <?php echo ($editNews['featured'] ?? 0) ? 'checked' : ''; ?>> Featured (Show in sidebar)</label>
    
    <button type="submit"><?php echo $editNews ? 'Update' : 'Add'; ?> News</button>
</form>

<table>
    <tr>
        <th>Title</th>
        <th>Category</th>
        <th>Featured</th>
        <th>Type</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>
    <?php
    $news = $conn->query("SELECT n.*, c.name as category FROM news n LEFT JOIN categories c ON n.category_id = c.id ORDER BY n.created_at DESC");
    while ($item = $news->fetch_assoc()):
        $type = 'Image';
        if ($item['video_file']) $type = 'Video';
        if ($item['youtube_url']) $type = 'YouTube';
    ?>
    <tr>
        <td><?php echo $item['title']; ?></td>
        <td><?php echo $item['category']; ?></td>
        <td><?php echo $item['featured'] ? 'Yes' : 'No'; ?></td>
        <td><?php echo $type; ?></td>
        <td><?php echo date('d M Y', strtotime($item['created_at'])); ?></td>
        <td>
            <a href="?edit=<?php echo $item['id']; ?>">Edit</a>
            <a href="?delete=<?php echo $item['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include 'includes/footer.php'; ?>
<form method="POST" enctype="multipart/form-data" class="admin-form">
    <input type="hidden" name="id" value="<?php echo $editNews['id'] ?? ''; ?>">
    <input type="text" name="title" placeholder="News Title" value="<?php echo $editNews['title'] ?? ''; ?>" required>
    <textarea name="content" placeholder="News Content" rows="6" required><?php echo $editNews['content'] ?? ''; ?></textarea>
    <input type="file" name="image" <?php echo $editNews ? '' : 'required'; ?>>
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php
        $cats = $conn->query("SELECT * FROM categories");
        while ($cat = $cats->fetch_assoc()):
        ?>
            <option value="<?php echo $cat['id']; ?>" <?php echo ($editNews['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                <?php echo $cat['name']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    <label><input type="checkbox" name="featured" value="1" <?php echo ($editNews['featured'] ?? 0) ? 'checked' : ''; ?>> Featured</label>
    <button type="submit"><?php echo $editNews ? 'Update' : 'Add'; ?> News</button>
</form>

<table>
    <tr>
        <th>Title</th>
        <th>Category</th>
        <th>Featured</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>
    <?php
    $news = $conn->query("SELECT n.*, c.name as category FROM news n LEFT JOIN categories c ON n.category_id = c.id ORDER BY n.created_at DESC");
    while ($item = $news->fetch_assoc()):
    ?>
    <tr>
        <td><?php echo $item['title']; ?></td>
        <td><?php echo $item['category']; ?></td>
        <td><?php echo $item['featured'] ? 'Yes' : 'No'; ?></td>
        <td><?php echo date('d M Y', strtotime($item['created_at'])); ?></td>
        <td>
            <a href="?edit=<?php echo $item['id']; ?>">Edit</a>
            <a href="?delete=<?php echo $item['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include 'includes/footer.php'; ?>
