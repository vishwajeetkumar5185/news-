<?php
require_once 'config/database.php';
$conn = getConnection();

echo "<h2>Updating Contact Information...</h2>";

$sql = "UPDATE contact_info SET 
    phone_number = '+91 8070111786',
    email = 'live18india2020@gmail.com',
    office_address_line1 = 'Office No. 003',
    office_address_line2 = 'New Raval Nagar, Building No. B',
    office_address_line3 = 'Behind Hardik Palace Hotel',
    office_landmark = 'Station Road',
    office_city = 'Mira Road East',
    office_state = 'Maharashtra',
    office_pincode = '401107'
WHERE id = 1";

if ($conn->query($sql)) {
    echo "<p style='color: green; font-size: 18px;'>✓ Contact information updated successfully!</p>";
    echo "<h3>Updated Information:</h3>";
    echo "<ul>";
    echo "<li><strong>Phone:</strong> +91 8070111786</li>";
    echo "<li><strong>Email:</strong> live18india2020@gmail.com</li>";
    echo "<li><strong>Address:</strong> Office No. 003, New Raval Nagar, Building No. B, Behind Hardik Palace Hotel, Station Road, Mira Road East, Thane – 401107, Maharashtra, India</li>";
    echo "</ul>";
    echo "<p><a href='check_contact_info.php' style='background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Verify Changes</a></p>";
    echo "<p><a href='index.php' style='background: #2196F3; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px;'>Go to Homepage</a></p>";
} else {
    echo "<p style='color: red;'>Error updating contact information: " . $conn->error . "</p>";
}

$conn->close();
?>
