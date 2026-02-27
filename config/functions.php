<?php
// The Lallantop API base URL
define('LALLANTOP_API_URL', 'https://api.thelallantop.com/v1/web/postListByCategory');
// Fetch news from external API (The Lallantop) with fallback
function fetchExternalNews($category = 'india', $limit = 9, $skip = 4)
{
    // Try The Lallantop API first
    $lallantop_news = fetchLallantopNews($category, $limit, $skip);
    if (!empty($lallantop_news['results'])) {
        return $lallantop_news;
    }

    // Fallback to RSS feed if Lallantop fails
    return fetchRSSFallback($limit);
}

// Primary function for The Lallantop API
function fetchLallantopNews($category = 'india', $limit = 9, $skip = 4)
{
    $url = LALLANTOP_API_URL . "/" . $category . "?limit=" . $limit . "&skip=" . $skip . "&type=video,text,liveblog";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($httpCode !== 200 || !$response || $error) {
        error_log("Lallantop API Error: HTTP $httpCode, Error: $error, URL: $url");
        return ['results' => []];
    }

    $data = json_decode($response, true);

    if (isset($data['data']) && is_array($data['data'])) {
        $results = [];
        foreach ($data['data'] as $item) {
            $image_url = $item['featured_image'] ?? $item['image'] ?? $item['thumbnail'] ?? '';
            $description = $item['excerpt'] ?? $item['summary'] ?? $item['description'] ?? '';
            $pubDate = $item['created_at'] ?? $item['published_at'] ?? $item['date'] ?? date('Y-m-d H:i:s');

            $results[] = [
                'title' => $item['title'] ?? 'No Title',
                'link' => isset($item['slug']) ? 'https://www.thelallantop.com/' . $item['slug'] : ($item['url'] ?? '#'),
                'description' => strip_tags($description),
                'image_url' => $image_url,
                'pubDate' => $pubDate,
                'category' => [$category],
                'source' => 'The Lallantop'
            ];
        }
        return ['results' => $results];
    }

    return ['results' => []];
}

// Fallback RSS function for when APIs fail
function fetchRSSFallback($limit = 9)
{
    $rss_feeds = [
        'https://feeds.feedburner.com/ndtvnews-top-stories',
        'https://timesofindia.indiatimes.com/rssfeedstopstories.cms',
        'https://www.hindustantimes.com/feeds/rss/india-news/index.xml'
    ];

    foreach ($rss_feeds as $rssUrl) {
        $articles = fetchSingleRSSFeed($rssUrl, $limit);
        if (!empty($articles)) {
            return ['results' => $articles];
        }
    }

    return ['results' => []];
}

// Helper function to fetch from a single RSS feed
function fetchSingleRSSFeed($rssUrl, $limit = 9)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $rssUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $xmlContent = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$xmlContent) {
        return [];
    }

    $xml = simplexml_load_string($xmlContent);
    if (!$xml) {
        return [];
    }

    $articles = [];
    $count = 0;

    foreach ($xml->channel->item as $item) {
        if ($count >= $limit)
            break;

        $image = '';
        if (isset($item->children('media', true)->content)) {
            $image = (string) $item->children('media', true)->content->attributes()->url;
        }

        $articles[] = [
            'title' => (string) $item->title,
            'link' => (string) $item->link,
            'description' => strip_tags((string) $item->description),
            'image_url' => $image,
            'pubDate' => (string) $item->pubDate,
            'category' => ['india'],
            'source' => 'RSS Feed'
        ];

        $count++;
    }

    return $articles;
}

// Get available categories for Lallantop API
function getLallantopCategories()
{
    return [
        'india' => 'India',
        'world' => 'World',
        'politics' => 'Politics',
        'sports' => 'Sports',
        'entertainment' => 'Entertainment',
        'business' => 'Business',
        'technology' => 'Technology',
        'health' => 'Health'
    ];
}

// Fetch news from RSS feed
function fetchRSSFeed($limit = 10)
{
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
        if ($count >= $limit)
            break;

        // Extract image from content:encoded or description
        $image = '';
        if (isset($item->children('content', true)->encoded)) {
            $content = (string) $item->children('content', true)->encoded;
            preg_match('/<img[^>]+src="([^">]+)"/', $content, $matches);
            if (isset($matches[1])) {
                $image = $matches[1];
            }
        }

        // If no image found, try media:content
        if (!$image && isset($item->children('media', true)->content)) {
            $image = (string) $item->children('media', true)->content->attributes()->url;
        }

        $articles[] = [
            'title' => (string) $item->title,
            'link' => (string) $item->link,
            'description' => strip_tags((string) $item->description),
            'image_url' => $image,
            'pubDate' => (string) $item->pubDate
        ];

        $count++;
    }

    return $articles;
}

// Get site settings
function getSiteSettings($conn)
{
    $result = $conn->query("SELECT * FROM settings WHERE id = 1");
    return $result->fetch_assoc();
}

// Get categories
function getCategories($conn)
{
    $result = $conn->query("SELECT * FROM categories ORDER BY name");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get banners
function getBanners($conn, $position = '')
{
    $sql = "SELECT * FROM banners WHERE status = 1";
    if ($position) {
        $sql .= " AND position = '$position'";
    }
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get featured news
function getFeaturedNews($conn, $limit = 5)
{
    $result = $conn->query("SELECT * FROM news WHERE featured = 1 ORDER BY created_at DESC LIMIT $limit");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Sanitize input
function clean($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}
?>