<?php
require_once 'config/database.php';
$conn = getConnection();

echo "<h2>Admin Users in Database:</h2>";

$result = $conn->query("SELECT * FROM admin_users");

if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Username</th><th>Password Hash</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . substr($row['password'], 0, 50) . "...</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>No admin users found!</p>";
    echo "<h3>Creating default admin user...</h3>";
    
    $username = 'news';
    $password = md5('news@123');
    
    $conn->query("INSERT INTO admin_users (username, password) VALUES ('$username', '$password')");
    echo "<p style='color: green;'>Admin user created!</p>";
    echo "<p><strong>Username:</strong> news</p>";
    echo "<p><strong>Password:</strong> news@123</p>";
}

$conn->close();
?>
