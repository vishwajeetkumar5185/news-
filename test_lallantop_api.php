<?php
require_once 'config/database.php';
require_once 'config/functions.php';

echo "<h2>🔍 Debugging The Lallantop API</h2>";

// Test the exact URL directly
$test_url = "https://api.thelallantop.com/v1/web/postListByCategory/india?limit=9&skip=4&type=video,text,liveblog";

echo "<h3>1. Testing Direct API Call:</h3>";
echo "<p><strong>URL:</strong> $test_url</p>";

// Direct cURL test
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $test_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "<p><strong>HTTP Code:</strong> $httpCode</p>";
echo "<p><strong>cURL Error:</strong> " . ($error ? $error : "None") . "</p>";
echo "<p><strong>Response Length:</strong> " . strlen($response) . " characters</p>";

if ($response) {
    echo "<h3>2. Raw API Response (First 1000 chars):</h3>";
    echo "<pre style='background: #f5f5f5; padding: 15px; border-radius: 8px; overflow-x: auto;'>";
    echo htmlspecialchars(substr($response, 0, 1000));
    if (strlen($response) > 1000) echo "\n... (truncated)";
    echo "</pre>";
    
    $data = json_decode($response, true);
    if ($data) {
        echo "<h3>3. Parsed JSON Structure:</h3>";
        echo "<pre style='background: #e8f5e8; padding: 15px; border-radius: 8px;'>";
        print_r(array_keys($data));
        if (isset($data['data'])) {
            echo "\ndata array has " . count($data['data']) . " items\n";
            if (count($data['data']) > 0) {
                echo "First item keys: ";
                print_r(array_keys($data['data'][0]));
            }
        }
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>❌ Failed to parse JSON response</p>";
    }
} else {
    echo "<p style='color: red;'>❌ No response received</p>";
}

// Test with different parameters
echo "<h3>4. Testing Alternative URLs:</h3>";
$test_urls = [
    "https://api.thelallantop.com/v1/web/postListByCategory/india?limit=5&skip=0",
    "https://api.thelallantop.com/v1/web/postListByCategory/india?limit=9",
    "https://api.thelallantop.com/v1/web/postListByCategory/india"
];

foreach ($test_urls as $url) {
    echo "<p><strong>Testing:</strong> $url</p>";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "<span style='color: " . ($code == 200 ? "green" : "red") . ";'>HTTP $code</span> - ";
    echo strlen($resp) . " chars<br>";
}

// Test our function
echo "<h3>5. Testing Our Function:</h3>";
$news = fetchExternalNews('india', 9, 4);
echo "<pre>";
print_r($news);
echo "</pre>";
?>