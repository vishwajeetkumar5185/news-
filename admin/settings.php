<?php 
require 'includes/auth.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $site_name = clean($_POST['site_name']);
    $breaking_news = clean($_POST['breaking_news']);
    $footer_text = clean($_POST['footer_text']);
    $about_content = $_POST['about_content'];
    $live_video_url = clean($_POST['live_video_url']);
    
    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['logo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $logo = time() . '_' . $filename;
            $upload_path = '../uploads/' . $logo;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_path)) {
                // Check if live_video_url column exists
                $columns = $conn->query("SHOW COLUMNS FROM settings LIKE 'live_video_url'");
                if ($columns->num_rows > 0) {
                    $stmt = $conn->prepare("UPDATE settings SET logo=?, site_name=?, breaking_news=?, footer_text=?, about_content=?, live_video_url=? WHERE id=1");
                    if ($stmt) {
                        $stmt->bind_param("ssssss", $logo, $site_name, $breaking_news, $footer_text, $about_content, $live_video_url);
                        $stmt->execute();
                    }
                } else {
                    $stmt = $conn->prepare("UPDATE settings SET logo=?, site_name=?, breaking_news=?, footer_text=?, about_content=? WHERE id=1");
                    if ($stmt) {
                        $stmt->bind_param("sssss", $logo, $site_name, $breaking_news, $footer_text, $about_content);
                        $stmt->execute();
                    }
                }
            }
        }
    } else {
        // Check if live_video_url column exists
        $columns = $conn->query("SHOW COLUMNS FROM settings LIKE 'live_video_url'");
        if ($columns->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE settings SET site_name=?, breaking_news=?, footer_text=?, about_content=?, live_video_url=? WHERE id=1");
            if ($stmt) {
                $stmt->bind_param("sssss", $site_name, $breaking_news, $footer_text, $about_content, $live_video_url);
                $stmt->execute();
            }
        } else {
            $stmt = $conn->prepare("UPDATE settings SET site_name=?, breaking_news=?, footer_text=?, about_content=? WHERE id=1");
            if ($stmt) {
                $stmt->bind_param("ssss", $site_name, $breaking_news, $footer_text, $about_content);
                $stmt->execute();
            }
        }
    }
    
    header('Location: settings.php');
    exit;
}

$settings = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();

// If no settings exist, create default
if (!$settings) {
    $conn->query("INSERT INTO settings (site_name, breaking_news, footer_text, about_content) VALUES ('News Portal', 'Welcome!', '© 2024 News Portal', 'About us...')");
    $settings = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();
}
?>

<h1>Site Settings</h1>

<form method="POST" enctype="multipart/form-data" class="admin-form">
    <label>Site Name</label>
    <input type="text" name="site_name" value="<?php echo $settings['site_name']; ?>" required>
    
    <label>Logo</label>
    <input type="file" name="logo">
    <?php if ($settings['logo']): ?>
        <img src="../uploads/<?php echo $settings['logo']; ?>" width="100">
    <?php endif; ?>
    
    <label>Breaking News</label>
    <input type="text" name="breaking_news" value="<?php echo $settings['breaking_news']; ?>">
    
    <label>Live Video URL (YouTube or Video File)</label>
    <input type="text" name="live_video_url" value="<?php echo $settings['live_video_url'] ?? ''; ?>" placeholder="https://youtube.com/watch?v=... or video filename">
    <small style="color: #666;">Enter YouTube URL or upload video file to uploads folder and enter filename</small>
    
    <label>Footer Text</label>
    <input type="text" name="footer_text" value="<?php echo $settings['footer_text']; ?>">
    
    <label>About Page Content</label>
    <textarea name="about_content" rows="6"><?php echo $settings['about_content']; ?></textarea>
    
    <button type="submit">Update Settings</button>
</form>

<?php include 'includes/footer.php'; ?>
