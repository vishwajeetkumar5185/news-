<?php 
require 'includes/auth.php';
include 'includes/header.php';

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = clean($_POST['title']);
    $youtube_url = clean($_POST['youtube_url']);
    $status = $_POST['status'] ?? 1;
    
    $video_file = '';
    $thumbnail = '';
    
    if ($_FILES['video_file']['name']) {
        $video_file = time() . '_' . $_FILES['video_file']['name'];
        move_uploaded_file($_FILES['video_file']['tmp_name'], '../uploads/' . $video_file);
    }
    
    if ($_FILES['thumbnail']['name']) {
        $thumbnail = time() . '_' . $_FILES['thumbnail']['name'];
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], '../uploads/' . $thumbnail);
    }
    
    if (isset($_POST['id']) && $_POST['id']) {
        $id = $_POST['id'];
        $sql = "UPDATE videos SET title='$title', youtube_url='$youtube_url', status=$status";
        if ($video_file) $sql .= ", video_file='$video_file'";
        if ($thumbnail) $sql .= ", thumbnail='$thumbnail'";
        $sql .= " WHERE id=$id";
        $conn->query($sql);
    } else {
        $conn->query("INSERT INTO videos (title, youtube_url, video_file, thumbnail, status) VALUES ('$title', '$youtube_url', '$video_file', '$thumbnail', $status)");
    }
    header('Location: videos.php');
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM videos WHERE id=" . $_GET['delete']);
    header('Location: videos.php');
    exit;
}

$editVideo = null;
if (isset($_GET['edit'])) {
    $editVideo = $conn->query("SELECT * FROM videos WHERE id=" . $_GET['edit'])->fetch_assoc();
}
?>

<h1>Manage Videos</h1>

<form method="POST" enctype="multipart/form-data" class="admin-form">
    <input type="hidden" name="id" value="<?php echo $editVideo['id'] ?? ''; ?>">
    <input type="text" name="title" placeholder="Video Title" value="<?php echo $editVideo['title'] ?? ''; ?>" required>
    <input type="text" name="youtube_url" placeholder="YouTube URL (optional)" value="<?php echo $editVideo['youtube_url'] ?? ''; ?>">
    <label>Upload Video File (optional):</label>
    <input type="file" name="video_file" accept="video/*">
    <label>Upload Thumbnail:</label>
    <input type="file" name="thumbnail" accept="image/*">
    <label><input type="checkbox" name="status" value="1" <?php echo ($editVideo['status'] ?? 1) ? 'checked' : ''; ?>> Active</label>
    <button type="submit"><?php echo $editVideo ? 'Update' : 'Add'; ?> Video</button>
</form>

<table>
    <tr>
        <th>Title</th>
        <th>Type</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php
    $videos = $conn->query("SELECT * FROM videos ORDER BY id DESC");
    while ($video = $videos->fetch_assoc()):
    ?>
    <tr>
        <td><?php echo $video['title']; ?></td>
        <td><?php echo $video['youtube_url'] ? 'YouTube' : 'Uploaded'; ?></td>
        <td><?php echo $video['status'] ? 'Active' : 'Inactive'; ?></td>
        <td>
            <a href="?edit=<?php echo $video['id']; ?>">Edit</a>
            <a href="?delete=<?php echo $video['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include 'includes/footer.php'; ?>
