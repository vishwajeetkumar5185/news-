<?php
// Check uploads folder
$uploadsDir = 'uploads';

echo "<h2>Upload Folder Check</h2>";

if (!file_exists($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
    echo "<p style='color: green;'>✓ Created uploads folder</p>";
} else {
    echo "<p style='color: green;'>✓ Uploads folder exists</p>";
}

if (is_writable($uploadsDir)) {
    echo "<p style='color: green;'>✓ Uploads folder is writable</p>";
} else {
    echo "<p style='color: red;'>✗ Uploads folder is NOT writable</p>";
    chmod($uploadsDir, 0777);
    echo "<p style='color: orange;'>→ Trying to fix permissions...</p>";
}

// List uploaded files
echo "<h3>Files in uploads folder:</h3>";
$files = scandir($uploadsDir);
if (count($files) > 2) {
    echo "<ul>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>$file</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p>No files uploaded yet</p>";
}
?>
