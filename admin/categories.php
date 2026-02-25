<?php 
require 'includes/auth.php';
include 'includes/header.php';

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $slug = strtolower(str_replace(' ', '-', $name));
    
    if (isset($_POST['id']) && $_POST['id']) {
        $id = $_POST['id'];
        $conn->query("UPDATE categories SET name='$name', slug='$slug' WHERE id=$id");
    } else {
        $conn->query("INSERT INTO categories (name, slug) VALUES ('$name', '$slug')");
    }
    header('Location: categories.php');
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM categories WHERE id=" . $_GET['delete']);
    header('Location: categories.php');
    exit;
}

$editCategory = null;
if (isset($_GET['edit'])) {
    $editCategory = $conn->query("SELECT * FROM categories WHERE id=" . $_GET['edit'])->fetch_assoc();
}
?>

<h1>Manage Categories</h1>

<form method="POST" class="admin-form">
    <input type="hidden" name="id" value="<?php echo $editCategory['id'] ?? ''; ?>">
    <input type="text" name="name" placeholder="Category Name" value="<?php echo $editCategory['name'] ?? ''; ?>" required>
    <button type="submit"><?php echo $editCategory ? 'Update' : 'Add'; ?> Category</button>
</form>

<table>
    <tr>
        <th>Name</th>
        <th>Slug</th>
        <th>Actions</th>
    </tr>
    <?php
    $categories = $conn->query("SELECT * FROM categories ORDER BY name");
    while ($cat = $categories->fetch_assoc()):
    ?>
    <tr>
        <td><?php echo $cat['name']; ?></td>
        <td><?php echo $cat['slug']; ?></td>
        <td>
            <a href="?edit=<?php echo $cat['id']; ?>">Edit</a>
            <a href="?delete=<?php echo $cat['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include 'includes/footer.php'; ?>
