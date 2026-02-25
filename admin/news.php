<?php 
require 'includes/auth.php';
include 'includes/header.php';

// Test database connection
if (!$conn) {
    die("Database connection failed!");
}

// Debug: Show POST data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
    echo "<strong>DEBUG - Form Data Received:</strong><br>";
    echo "Title: " . ($_POST['title'] ?? 'Not set') . "<br>";
    echo "Content length: " . strlen($_POST['content'] ?? '') . " characters<br>";
    echo "Category ID: " . ($_POST['category_id'] ?? 'Not set') . "<br>";
    echo "</div>";
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = clean($_POST['title']);
    $subtitle = clean($_POST['subtitle']);
    $content = $_POST['content']; // Don't clean HTML content
    $category_id = $_POST['category_id'];
    $featured = $_POST['featured'] ?? 0;
    $youtube_url = clean($_POST['youtube_url']);
    
    // Debug: Check if content is being received
    error_log("Content received: " . substr($content, 0, 100));
    
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
    
    // Escape content for database
    $content_escaped = $conn->real_escape_string($content);
    $title_escaped = $conn->real_escape_string($title);
    $subtitle_escaped = $conn->real_escape_string($subtitle);
    $youtube_url_escaped = $conn->real_escape_string($youtube_url);
    
    if (isset($_POST['id']) && $_POST['id']) {
        $id = intval($_POST['id']);
        $sql = "UPDATE news SET title='$title_escaped', subtitle='$subtitle_escaped', content='$content_escaped', category_id=$category_id, featured=$featured, youtube_url='$youtube_url_escaped'";
        if ($image) $sql .= ", image='$image'";
        if ($video_file) $sql .= ", video_file='$video_file'";
        $sql .= " WHERE id=$id";
        
        if ($conn->query($sql)) {
            echo "<script>alert('News updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating news: " . $conn->error . "');</script>";
        }
    } else {
        $sql = "INSERT INTO news (title, subtitle, content, image, video_file, youtube_url, category_id, featured) VALUES ('$title_escaped', '$subtitle_escaped', '$content_escaped', '$image', '$video_file', '$youtube_url_escaped', $category_id, $featured)";
        
        if ($conn->query($sql)) {
            echo "<script>alert('News added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding news: " . $conn->error . "');</script>";
        }
    }
    
    // Redirect after successful operation
    echo "<script>setTimeout(function(){ window.location.href = 'news.php'; }, 1000);</script>";
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
    
    <label>Content (HTML Formatting Supported)</label>
    <div class="editor-container">
        <div id="content-editor"></div>
    </div>
    <textarea id="content-hidden" name="content" style="display: none;"></textarea>
    
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
    
    <button type="submit" onclick="console.log('Form submitted'); return true;"><?php echo $editNews ? 'Update' : 'Add'; ?> News</button>
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

<!-- Rich Text Editor with Quill.js -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<style>
.editor-container {
    background: white;
    border: 1px solid #ccc;
    border-radius: 8px;
    overflow: hidden;
}

.ql-toolbar {
    border-bottom: 1px solid #ccc;
    background: #f8f9fa;
}

.ql-container {
    min-height: 300px;
    font-size: 14px;
    line-height: 1.6;
}

.ql-editor {
    min-height: 300px;
    padding: 20px;
}

.ql-editor h1, .ql-editor h2, .ql-editor h3 {
    margin: 20px 0 10px 0;
    font-weight: bold;
}

.ql-editor p {
    margin-bottom: 15px;
}

.ql-editor ul, .ql-editor ol {
    margin: 15px 0;
    padding-left: 30px;
}

.ql-editor blockquote {
    border-left: 4px solid #e53935;
    padding-left: 16px;
    margin: 20px 0;
    font-style: italic;
    background: #f8f9fa;
    padding: 15px;
    border-radius: 4px;
}
</style>

<script>
// Initialize Quill editor
var quill = new Quill('#content-editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'align': [] }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['blockquote', 'code-block'],
            ['link', 'image'],
            ['clean']
        ]
    },
    placeholder: 'Write your news content here...'
});

// Set initial content if editing
<?php if (isset($editNews['content']) && $editNews['content']): ?>
quill.root.innerHTML = <?php echo json_encode($editNews['content']); ?>;
<?php endif; ?>

// Update hidden textarea when form is submitted
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const hiddenTextarea = document.querySelector('#content-hidden');
    
    form.addEventListener('submit', function(e) {
        // Get content from Quill editor
        const content = quill.root.innerHTML;
        
        // Set content to hidden textarea
        hiddenTextarea.value = content;
        
        // Check if content is empty
        if (quill.getText().trim().length === 0) {
            e.preventDefault();
            alert('Please enter some content for the news article.');
            return false;
        }
        
        console.log('Content being submitted:', content); // Debug log
    });
    
    // Also update on any content change
    quill.on('text-change', function() {
        hiddenTextarea.value = quill.root.innerHTML;
    });
});
</script>

<?php include 'includes/footer.php'; ?>
