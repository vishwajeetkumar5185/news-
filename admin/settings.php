<?php 
require 'includes/auth.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debug: Show what was posted
    echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
    echo "<strong>DEBUG - Form Data:</strong><br>";
    echo "live_video_url: " . ($_POST['live_video_url'] ?? 'Not set') . "<br>";
    echo "live_status: " . (isset($_POST['live_status']) ? 'Checked' : 'Not checked') . "<br>";
    echo "</div>";
    
    $site_name = clean($_POST['site_name']);
    $breaking_news = clean($_POST['breaking_news']);
    $footer_text = clean($_POST['footer_text']);
    $about_content = $_POST['about_content'];
    $live_video_url = clean($_POST['live_video_url']);
    $live_status = isset($_POST['live_status']) ? 1 : 0;
    
    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['logo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $logo = time() . '_' . $filename;
            $upload_path = '../uploads/' . $logo;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_path)) {
                $stmt = $conn->prepare("UPDATE settings SET logo=?, site_name=?, breaking_news=?, footer_text=?, about_content=?, live_video_url=?, live_status=? WHERE id=1");
                if ($stmt) {
                    $stmt->bind_param("ssssssi", $logo, $site_name, $breaking_news, $footer_text, $about_content, $live_video_url, $live_status);
                    if ($stmt->execute()) {
                        echo "<div style='background: #d4edda; color: #155724; padding: 10px; margin: 10px 0; border-radius: 5px;'>✅ Settings updated successfully with logo!</div>";
                    } else {
                        echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border-radius: 5px;'>❌ Error: " . $stmt->error . "</div>";
                    }
                }
            }
        }
    } else {
        $stmt = $conn->prepare("UPDATE settings SET site_name=?, breaking_news=?, footer_text=?, about_content=?, live_video_url=?, live_status=? WHERE id=1");
        if ($stmt) {
            $stmt->bind_param("sssssi", $site_name, $breaking_news, $footer_text, $about_content, $live_video_url, $live_status);
            if ($stmt->execute()) {
                echo "<div style='background: #d4edda; color: #155724; padding: 10px; margin: 10px 0; border-radius: 5px;'>✅ Settings updated successfully!</div>";
            } else {
                echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border-radius: 5px;'>❌ Error: " . $stmt->error . "</div>";
            }
        }
    }
    
    // Don't redirect immediately, let user see the result
    // header('Location: settings.php');
    // exit;
}

$settings = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();

// Debug: Show what columns exist
echo "<!-- DEBUG: Available columns: ";
$columns = $conn->query("SHOW COLUMNS FROM settings");
while ($col = $columns->fetch_assoc()) {
    echo $col['Field'] . " ";
}
echo "-->";

// If no settings exist, create default
if (!$settings) {
    $conn->query("INSERT INTO settings (site_name, breaking_news, footer_text, about_content, live_video_url, live_status) VALUES ('Live 18 India', 'Welcome!', '© 2024 Live 18 India', 'About us...', '', 0)");
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
    <input type="text" id="live_video_url" name="live_video_url" value="<?php echo $settings['live_video_url'] ?? ''; ?>" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
    <button type="button" onclick="testYouTubeURL()" style="background: #4CAF50; color: white; padding: 8px 15px; border: none; border-radius: 4px; margin-left: 10px; cursor: pointer;">🔍 Test URL</button>
    <div id="url-test-result" style="margin-top: 10px;"></div>
    <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 10px 0; border-left: 4px solid #2196f3;">
        <strong>📺 Test These Working URLs:</strong><br>
        <div style="margin: 10px 0;">
            <button type="button" onclick="document.getElementById('live_video_url').value='https://www.youtube.com/watch?v=jfKfPfyJRdk'; testYouTubeURL();" style="background: #2196f3; color: white; padding: 5px 10px; border: none; border-radius: 3px; margin: 2px; cursor: pointer; font-size: 12px;">📻 Lofi Hip Hop</button>
            <button type="button" onclick="document.getElementById('live_video_url').value='https://youtu.be/5qap5aO4i9A'; testYouTubeURL();" style="background: #2196f3; color: white; padding: 5px 10px; border: none; border-radius: 3px; margin: 2px; cursor: pointer; font-size: 12px;">🎵 Relaxing Music</button>
            <button type="button" onclick="document.getElementById('live_video_url').value='https://www.youtube.com/watch?v=DWcJFNfaw9c'; testYouTubeURL();" style="background: #ff4444; color: white; padding: 5px 10px; border: none; border-radius: 3px; margin: 2px; cursor: pointer; font-size: 12px;">📺 24/7 News</button>
            <button type="button" onclick="document.getElementById('live_video_url').value='https://www.youtube.com/live/ryC78z4CRIc'; testYouTubeURL();" style="background: #ff6600; color: white; padding: 5px 10px; border: none; border-radius: 3px; margin: 2px; cursor: pointer; font-size: 12px;">🔴 Test Live URL</button>
        </div>
        <strong>📝 URL Format Examples:</strong><br>
        • <code>https://www.youtube.com/watch?v=VIDEO_ID</code><br>
        • <code>https://youtu.be/VIDEO_ID</code><br>
        • <code>https://www.youtube.com/embed/VIDEO_ID</code><br>
        • <code>https://www.youtube.com/live/VIDEO_ID</code> (🔴 Live streams)<br><br>
        <strong>⚠️ Important:</strong><br>
        • Use public YouTube videos only<br>
        • Make sure embedding is allowed<br>
        • For live streams, use the live stream URL<br>
        • Video ID must be exactly 11 characters
    </div>
    
    <script>
    function testYouTubeURL() {
        const url = document.getElementById('live_video_url').value;
        const resultDiv = document.getElementById('url-test-result');
        
        if (!url) {
            resultDiv.innerHTML = '<div style="background: #ffebee; color: #c62828; padding: 10px; border-radius: 4px;">❌ Please enter a URL first</div>';
            return;
        }
        
        // Multiple regex patterns for different YouTube URL formats
        let videoId = null;
        
        // Pattern 1: youtube.com/watch?v=VIDEO_ID
        let match = url.match(/(?:youtube\.com\/watch\?v=)([a-zA-Z0-9_-]{11})/);
        if (match) videoId = match[1];
        
        // Pattern 2: youtu.be/VIDEO_ID
        if (!videoId) {
            match = url.match(/(?:youtu\.be\/)([a-zA-Z0-9_-]{11})/);
            if (match) videoId = match[1];
        }
        
        // Pattern 3: youtube.com/embed/VIDEO_ID
        if (!videoId) {
            match = url.match(/(?:youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/);
            if (match) videoId = match[1];
        }
        
        // Pattern 4: youtube.com/v/VIDEO_ID
        if (!videoId) {
            match = url.match(/(?:youtube\.com\/v\/)([a-zA-Z0-9_-]{11})/);
            if (match) videoId = match[1];
        }
        
        // Pattern 5: youtube.com/live/VIDEO_ID (NEW - for live streams)
        if (!videoId) {
            match = url.match(/(?:youtube\.com\/live\/)([a-zA-Z0-9_-]{11})/);
            if (match) videoId = match[1];
        }
        
        console.log('URL:', url);
        console.log('Video ID:', videoId);
        
        if (videoId && videoId.length === 11) {
            resultDiv.innerHTML = `
                <div style="background: #e8f5e8; color: #2e7d32; padding: 10px; border-radius: 4px; margin-bottom: 10px;">
                    ✅ Valid YouTube URL detected<br>
                    <strong>Video ID:</strong> ${videoId}<br>
                    <strong>Original URL:</strong> ${url}<br>
                    <strong>Type:</strong> ${url.includes('/live/') ? '🔴 Live Stream' : '📺 Regular Video'}
                </div>
                <div style="border: 1px solid #ddd; border-radius: 4px; overflow: hidden;">
                    <iframe width="300" height="169" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div style="background: #ffebee; color: #c62828; padding: 10px; border-radius: 4px;">
                    ❌ Invalid YouTube URL format<br>
                    <strong>URL entered:</strong> ${url}<br><br>
                    <strong>Valid formats:</strong><br>
                    • https://www.youtube.com/watch?v=VIDEO_ID<br>
                    • https://youtu.be/VIDEO_ID<br>
                    • https://www.youtube.com/embed/VIDEO_ID<br>
                    • https://www.youtube.com/v/VIDEO_ID<br>
                    • https://www.youtube.com/live/VIDEO_ID (Live streams)
                </div>
            `;
        }
    }
    </script>
    
    <label style="display: flex; align-items: center; gap: 10px; margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #e53935;">
        <input type="checkbox" name="live_status" value="1" <?php echo ($settings['live_status'] ?? 0) ? 'checked' : ''; ?> style="width: 20px; height: 20px;">
        <span style="font-weight: 600; color: #e53935;">🔴 Enable Live TV (Show to all users)</span>
    </label>
    <small style="color: #666; margin-top: -10px; display: block;">Check this box to make live TV visible to all website visitors. Uncheck to hide it.</small>
    
    <label>Footer Text</label>
    <input type="text" name="footer_text" value="<?php echo $settings['footer_text']; ?>">
    
    <label>About Page Content</label>
    <textarea name="about_content" rows="6"><?php echo $settings['about_content']; ?></textarea>
    
    <button type="submit">Update Settings</button>
</form>

<?php include 'includes/footer.php'; ?>
