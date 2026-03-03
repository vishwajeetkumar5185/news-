<?php 
$keyword = $_GET['q'] ?? '';
$keyword = clean($keyword);

// SEO Meta Data for Search Page
$pageTitle = !empty($keyword) ? "Search Results for '{$keyword}' - Live 18 India" : "Search News Articles - Live 18 India";
$pageDescription = !empty($keyword) ? "Search results for '{$keyword}' on Live 18 India. Find relevant news articles, videos, and updates related to your search query." : "Search for news articles, videos, and updates on Live 18 India. Find the latest news on any topic from India's most trusted news source.";
$pageKeywords = !empty($keyword) ? "search {$keyword}, {$keyword} news, Live 18 India search, news search" : "search news, news articles search, Live 18 India search, find news";
$canonicalUrl = "https://live18india.com/search.php" . (!empty($keyword) ? "?q=" . urlencode($keyword) : "");

// Open Graph Meta Tags
$ogTitle = $pageTitle;
$ogDescription = $pageDescription;
$ogImage = "https://live18india.com/assets/images/search-page.jpg";
$ogUrl = $canonicalUrl;
$ogType = "website";

// Twitter Card Meta Tags
$twitterTitle = $ogTitle;
$twitterDescription = $ogDescription;
$twitterImage = $ogImage;

// Schema.org JSON-LD for Search Page
$schemaData = [
    "@context" => "https://schema.org",
    "@type" => "SearchResultsPage",
    "name" => $pageTitle,
    "description" => $pageDescription,
    "url" => $canonicalUrl,
    "publisher" => [
        "@type" => "NewsMediaOrganization",
        "name" => "Live 18 India",
        "url" => "https://live18india.com"
    ]
];

if (!empty($keyword)) {
    $schemaData["mainEntity"] = [
        "@type" => "SearchAction",
        "query" => $keyword,
        "target" => "https://live18india.com/search.php?q={search_term_string}"
    ];
}

// Set page category for analytics
$pageCategory = 'search_results';

include 'includes/header.php';
?>

<!-- Schema.org JSON-LD for Search Page -->
<script type="application/ld+json">
<?php echo json_encode($schemaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
</script>

<?php if (!empty($keyword)): ?>
<!-- Track search query -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Count search results
    let resultsCount = document.querySelectorAll('.search-result-item').length;
    
    // Track search event
    trackSearch('<?php echo htmlspecialchars($keyword); ?>', resultsCount);
    
    // Track search result clicks
    document.querySelectorAll('.search-result-item a').forEach(function(link, index) {
        link.addEventListener('click', function() {
            gtag('event', 'search_result_click', {
                'search_term': '<?php echo htmlspecialchars($keyword); ?>',
                'result_position': index + 1,
                'result_url': this.href,
                'event_category': 'Search',
                'event_label': 'Result Click'
            });
        });
    });
});
</script>
<?php endif; ?>

<div class="container">
    <h1>Search Results for: <?php echo $keyword; ?></h1>

    <section>
        <h2>From Our Database</h2>
        <div class="news-grid">
            <?php
            $dbResults = $conn->query("SELECT * FROM news WHERE title LIKE '%$keyword%' OR content LIKE '%$keyword%'");
            while ($news = $dbResults->fetch_assoc()):
            ?>
                <article class="news-card">
                    <img src="uploads/<?php echo $news['image']; ?>" alt="<?php echo $news['title']; ?>">
                    <h3><a href="single.php?id=<?php echo $news['id']; ?>"><?php echo $news['title']; ?></a></h3>
                </article>
            <?php endwhile; ?>
        </div>
    </section>

    <section>
        <h2>From Web</h2>
        <div class="news-grid">
            <?php
            $apiUrl = NEWS_API_URL . "?apikey=" . NEWS_API_KEY . "&q=$keyword&language=en";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);
            $apiResults = json_decode($response, true);
            
            if ($apiResults && isset($apiResults['results'])):
                foreach (array_slice($apiResults['results'], 0, 6) as $article):
            ?>
                <article class="news-card">
                    <?php if (!empty($article['image_url'])): ?>
                        <img src="<?php echo $article['image_url']; ?>" alt="<?php echo $article['title']; ?>">
                    <?php endif; ?>
                    <h3><a href="<?php echo $article['link']; ?>" target="_blank"><?php echo $article['title']; ?></a></h3>
                </article>
            <?php
                endforeach;
            endif;
            ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
