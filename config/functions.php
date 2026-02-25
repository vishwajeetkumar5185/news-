<?php
// Fetch news from external API (newsdata.io)
function fetchExternalNews($category = '', $limit = 10) {
    // Free plan allows max 10 results
    if ($limit > 10) {
        $limit = 10;
    }
    
    $url = NEWS_API_URL . "?apikey=" . NEWS_API_KEY . "&q=india%20news&language=en";
    if ($category) {
        $url .= "&category=" . $category;
    }
    
    // Only add size parameter if limit is less than 10
    if ($limit < 10) {
        $url .= "&size=" . $limit;
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Fetch news from RSS feed
function fetchRSSFeed($limit = 10) {
    $rssUrl = 'https://theopinionatedindian.com/feed.xml';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $rssUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $xmlContent = curl_exec($ch);
    curl_close($ch);
    
    if (!$xmlContent) {
        return [];
    }
    
    $xml = simplexml_load_string($xmlContent);
    if (!$xml) {
        return [];
    }
    
    $articles = [];
    $count = 0;
    
    foreach ($xml->channel->item as $item) {
        if ($count >= $limit) break;
        
        // Extract image from content:encoded or description
        $image = '';
        if (isset($item->children('content', true)->encoded)) {
            $content = (string)$item->children('content', true)->encoded;
            preg_match('/<img[^>]+src="([^">]+)"/', $content, $matches);
            if (isset($matches[1])) {
                $image = $matches[1];
            }
        }
        
        // If no image found, try media:content
        if (!$image && isset($item->children('media', true)->content)) {
            $image = (string)$item->children('media', true)->content->attributes()->url;
        }
        
        $articles[] = [
            'title' => (string)$item->title,
            'link' => (string)$item->link,
            'description' => strip_tags((string)$item->description),
            'image_url' => $image,
            'pubDate' => (string)$item->pubDate
        ];
        
        $count++;
    }
    
    return $articles;
}

// Get site settings
function getSiteSettings($conn) {
    $result = $conn->query("SELECT * FROM settings WHERE id = 1");
    return $result->fetch_assoc();
}

// Get categories
function getCategories($conn) {
    $result = $conn->query("SELECT * FROM categories ORDER BY name");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get banners
function getBanners($conn, $position = '') {
    $sql = "SELECT * FROM banners WHERE status = 1";
    if ($position) {
        $sql .= " AND position = '$position'";
    }
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get featured news
function getFeaturedNews($conn, $limit = 5) {
    $result = $conn->query("SELECT * FROM news WHERE featured = 1 ORDER BY created_at DESC LIMIT $limit");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Sanitize input
function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
