<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'news_portal');

// Create connection
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// External News API Configuration (The Lallantop)
define('LALLANTOP_API_URL', 'https://api.thelallantop.com/v1/web/postListByCategory');
define('NEWS_API_KEY', 'pub_68aacb1d3dc64f788c947bc7779ddce2'); // Keep old key as backup
?>
