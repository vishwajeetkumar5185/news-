<?php
require_once 'config/database.php';
$conn = getConnection();

echo "<h2>Contact Information in Database:</h2>";

$result = $conn->query("SELECT * FROM contact_info WHERE id = 1");

if ($result && $result->num_rows > 0) {
    $info = $result->fetch_assoc();
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Field</th><th>Current Value</th><th>Expected Value</th><th>Status</th></tr>";
    
    $checks = [
        ['phone_number', $info['phone_number'], '+91 8070111786'],
        ['email', $info['email'], 'live18india2020@gmail.com'],
        ['office_address_line1', $info['office_address_line1'], 'Office No. 003'],
        ['office_address_line2', $info['office_address_line2'], 'New Raval Nagar, Building No. B'],
        ['office_address_line3', $info['office_address_line3'], 'Behind Hardik Palace Hotel'],
        ['office_landmark', $info['office_landmark'], 'Station Road'],
        ['office_city', $info['office_city'], 'Mira Road East'],
        ['office_state', $info['office_state'], 'Maharashtra'],
        ['office_pincode', $info['office_pincode'], '401107']
    ];
    
    $needs_update = false;
    foreach ($checks as $check) {
        $field = $check[0];
        $current = $check[1];
        $expected = $check[2];
        $match = ($current == $expected);
        
        if (!$match) $needs_update = true;
        
        echo "<tr>";
        echo "<td><strong>" . $field . "</strong></td>";
        echo "<td>" . htmlspecialchars($current) . "</td>";
        echo "<td>" . htmlspecialchars($expected) . "</td>";
        echo "<td style='color: " . ($match ? 'green' : 'red') . "'>" . ($match ? '✓ Match' : '✗ Needs Update') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    if ($needs_update) {
        echo "<h3 style='color: orange;'>⚠️ Database needs to be updated!</h3>";
        echo "<p><a href='update_contact_database.php' style='background: #e53935; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Click here to update database</a></p>";
    } else {
        echo "<h3 style='color: green;'>✓ All contact information is up to date!</h3>";
    }
} else {
    echo "<p style='color: red;'>No contact information found in database!</p>";
}

$conn->close();
?>
