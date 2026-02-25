<?php 
require 'includes/auth.php';
include 'includes/header.php';

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = clean($_POST['title']);
    $link = clean($_POST['link']);
    $position = clean($_POST['position']);
    $status = $_POST['status'] ?? 1;
    
    $image = '';
    if ($_FILES['image']['name']) {
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image);
    }
    
    if (isset($_POST['id']) && $_POST['id']) {
        $id = $_POST['id'];
        if ($image) {
            $conn->query("UPDATE banners SET title='$title', image='$image', link='$link', position='$position', status=$status WHERE id=$id");
        } else {
            $conn->query("UPDATE banners SET title='$title', link='$link', position='$position', status=$status WHERE id=$id");
        }
    } else {
        $conn->query("INSERT INTO banners (title, image, link, position, status) VALUES ('$title', '$image', '$link', '$position', $status)");
    }
    header('Location: banners.php');
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM banners WHERE id=" . $_GET['delete']);
    header('Location: banners.php');
    exit;
}

$editBanner = null;
if (isset($_GET['edit'])) {
    $editBanner = $conn->query("SELECT * FROM banners WHERE id=" . $_GET['edit'])->fetch_assoc();
}
?>

<h1>Manage Banners</h1>

<form method="POST" enctype="multipart/form-data" class="admin-form">
    <input type="hidden" name="id" value="<?php echo $editBanner['id'] ?? ''; ?>">
    <input type="text" name="title" placeholder="Banner Title" value="<?php echo $editBanner['title'] ?? ''; ?>" required>
    <input type="file" name="image" <?php echo $editBanner ? '' : 'required'; ?>>
    <input type="text" name="link" placeholder="Link URL" value="<?php echo $editBanner['link'] ?? ''; ?>">
    <select name="position" required>
        <option value="sidebar" <?php echo ($editBanner['position'] ?? '') == 'sidebar' ? 'selected' : ''; ?>>Sidebar</option>
        <option value="header" <?php echo ($editBanner['position'] ?? '') == 'header' ? 'selected' : ''; ?>>Header</option>
    </select>
    <label><input type="checkbox" name="status" value="1" <?php echo ($editBanner['status'] ?? 1) ? 'checked' : ''; ?>> Active</label>
    <button type="submit"><?php echo $editBanner ? 'Update' : 'Add'; ?> Banner</button>
</form>

<table>
    <tr>
        <th>Title</th>
        <th>Position</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php
    $banners = $conn->query("SELECT * FROM banners ORDER BY id DESC");
    while ($banner = $banners->fetch_assoc()):
    ?>
    <tr>
        <td><?php echo $banner['title']; ?></td>
        <td><?php echo $banner['position']; ?></td>
        <td><?php echo $banner['status'] ? 'Active' : 'Inactive'; ?></td>
        <td>
            <a href="?edit=<?php echo $banner['id']; ?>">Edit</a>
            <a href="?delete=<?php echo $banner['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include 'includes/footer.php'; ?>
