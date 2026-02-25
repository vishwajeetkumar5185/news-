<?php
// Generate password hash
$password = 'news@123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Password Hash Generator</h2>";
echo "<p><strong>Password:</strong> $password</p>";
echo "<p><strong>Hash:</strong> $hash</p>";
echo "<hr>";
echo "<h3>Run this SQL query:</h3>";
echo "<pre>UPDATE admin_users SET password = '$hash' WHERE username = 'news';</pre>";
?>
