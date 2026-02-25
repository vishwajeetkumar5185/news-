<?php 
include 'includes/header.php';

$keyword = $_GET['q'] ?? '';
$keyword = clean($keyword);
?>

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
