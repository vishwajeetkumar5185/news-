<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'news');

// Create connection
function getConnection()
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// External News API Configuration (newsdata.io)
define('NEWS_API_KEY', 'pub_68aacb1d3dc64f788c947bc7779ddce2');
define('NEWS_API_URL', 'https://newsdata.io/api/1/latest');
?>