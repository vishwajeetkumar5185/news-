<?php 
require 'includes/auth.php';
include 'includes/header.php';

// Handle form submission
if ($_POST) {
    $contact_person_name = $_POST['contact_person_name'];
    $contact_person_dob = $_POST['contact_person_dob'];
    $phone_number = $_POST['phone_number'];
    $phone_availability = $_POST['phone_availability'];
    $email = $_POST['email'];
    $email_response_time = $_POST['email_response_time'];
    $office_address_line1 = $_POST['office_address_line1'];
    $office_address_line2 = $_POST['office_address_line2'];
    $office_address_line3 = $_POST['office_address_line3'];
    $office_city = $_POST['office_city'];
    $office_state = $_POST['office_state'];
    $office_pincode = $_POST['office_pincode'];
    $office_landmark = $_POST['office_landmark'];
    $working_hours_weekdays = $_POST['working_hours_weekdays'];
    $working_hours_saturday = $_POST['working_hours_saturday'];
    $working_hours_sunday = $_POST['working_hours_sunday'];
    $facebook_url = $_POST['facebook_url'];
    $twitter_url = $_POST['twitter_url'];
    $instagram_url = $_POST['instagram_url'];
    $youtube_url = $_POST['youtube_url'];
    $map_embed_url = $_POST['map_embed_url'];
    $visit_us_text = $_POST['visit_us_text'];
    
    // Update or insert contact info
    $stmt = $conn->prepare("INSERT INTO contact_info (id, contact_person_name, contact_person_dob, phone_number, phone_availability, email, email_response_time, office_address_line1, office_address_line2, office_address_line3, office_city, office_state, office_pincode, office_landmark, working_hours_weekdays, working_hours_saturday, working_hours_sunday, facebook_url, twitter_url, instagram_url, youtube_url, map_embed_url, visit_us_text) VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE contact_person_name=?, contact_person_dob=?, phone_number=?, phone_availability=?, email=?, email_response_time=?, office_address_line1=?, office_address_line2=?, office_address_line3=?, office_city=?, office_state=?, office_pincode=?, office_landmark=?, working_hours_weekdays=?, working_hours_saturday=?, working_hours_sunday=?, facebook_url=?, twitter_url=?, instagram_url=?, youtube_url=?, map_embed_url=?, visit_us_text=?");
    
    $stmt->bind_param("ssssssssssssssssssssssssssssssssssssssssssss", 
        $contact_person_name, $contact_person_dob, $phone_number, $phone_availability, $email, $email_response_time, 
        $office_address_line1, $office_address_line2, $office_address_line3, $office_city, $office_state, $office_pincode, 
        $office_landmark, $working_hours_weekdays, $working_hours_saturday, $working_hours_sunday, 
        $facebook_url, $twitter_url, $instagram_url, $youtube_url, $map_embed_url, $visit_us_text,
        $contact_person_name, $contact_person_dob, $phone_number, $phone_availability, $email, $email_response_time, 
        $office_address_line1, $office_address_line2, $office_address_line3, $office_city, $office_state, $office_pincode, 
        $office_landmark, $working_hours_weekdays, $working_hours_saturday, $working_hours_sunday, 
        $facebook_url, $twitter_url, $instagram_url, $youtube_url, $map_embed_url, $visit_us_text
    );
    
    if ($stmt->execute()) {
        $success = "Contact information updated successfully!";
    } else {
        $error = "Error updating contact information.";
    }
}

// Get current contact info
$contact_info = $conn->query("SELECT * FROM contact_info WHERE id = 1")->fetch_assoc();
?>

<h1>Contact Information Management</h1>

<?php if (isset($success)): ?>
    <div class="alert success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert error"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" class="contact-info-form">
    
    <!-- Contact Person Section -->
    <div class="form-section">
        <h2>👤 Contact Person</h2>
        <div class="form-row">
            <div class="form-group">
                <label for="contact_person_name">Contact Person Name:</label>
                <input type="text" name="contact_person_name" id="contact_person_name" value="<?php echo $contact_info['contact_person_name'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_person_dob">Date of Birth:</label>
                <input type="text" name="contact_person_dob" id="contact_person_dob" value="<?php echo $contact_info['contact_person_dob'] ?? ''; ?>" placeholder="DD-MM-YYYY">
            </div>
        </div>
    </div>

    <!-- Phone Section -->
    <div class="form-section">
        <h2>📱 Phone Information</h2>
        <div class="form-row">
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" id="phone_number" value="<?php echo $contact_info['phone_number'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_availability">Phone Availability:</label>
                <input type="text" name="phone_availability" id="phone_availability" value="<?php echo $contact_info['phone_availability'] ?? ''; ?>" placeholder="e.g., Mon-Sun, 24/7 Available">
            </div>
        </div>
    </div>

    <!-- Email Section -->
    <div class="form-section">
        <h2>📧 Email Information</h2>
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" name="email" id="email" value="<?php echo $contact_info['email'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="email_response_time">Email Response Time:</label>
                <input type="text" name="email_response_time" id="email_response_time" value="<?php echo $contact_info['email_response_time'] ?? ''; ?>" placeholder="e.g., We'll reply within 24 hours">
            </div>
        </div>
    </div>

    <!-- Office Address Section -->
    <div class="form-section">
        <h2>📍 Office Address</h2>
        <div class="form-row">
            <div class="form-group">
                <label for="office_address_line1">Address Line 1:</label>
                <input type="text" name="office_address_line1" id="office_address_line1" value="<?php echo $contact_info['office_address_line1'] ?? ''; ?>" placeholder="Building/Flat details">
            </div>
            <div class="form-group">
                <label for="office_address_line2">Address Line 2:</label>
                <input type="text" name="office_address_line2" id="office_address_line2" value="<?php echo $contact_info['office_address_line2'] ?? ''; ?>" placeholder="Area/Locality">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="office_address_line3">Address Line 3:</label>
                <input type="text" name="office_address_line3" id="office_address_line3" value="<?php echo $contact_info['office_address_line3'] ?? ''; ?>" placeholder="Street/Road">
            </div>
            <div class="form-group">
                <label for="office_city">City:</label>
                <input type="text" name="office_city" id="office_city" value="<?php echo $contact_info['office_city'] ?? ''; ?>" placeholder="City">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="office_state">State:</label>
                <input type="text" name="office_state" id="office_state" value="<?php echo $contact_info['office_state'] ?? ''; ?>" placeholder="State">
            </div>
            <div class="form-group">
                <label for="office_pincode">Pincode:</label>
                <input type="text" name="office_pincode" id="office_pincode" value="<?php echo $contact_info['office_pincode'] ?? ''; ?>" placeholder="Pincode">
            </div>
        </div>
        <div class="form-group">
            <label for="office_landmark">Landmark:</label>
            <input type="text" name="office_landmark" id="office_landmark" value="<?php echo $contact_info['office_landmark'] ?? ''; ?>" placeholder="Nearby landmark">
        </div>
    </div>

    <!-- Working Hours Section -->
    <div class="form-section">
        <h2>🕐 Working Hours</h2>
        <div class="form-row">
            <div class="form-group">
                <label for="working_hours_weekdays">Monday - Friday:</label>
                <input type="text" name="working_hours_weekdays" id="working_hours_weekdays" value="<?php echo $contact_info['working_hours_weekdays'] ?? ''; ?>" placeholder="e.g., 9:00 AM - 6:00 PM">
            </div>
            <div class="form-group">
                <label for="working_hours_saturday">Saturday:</label>
                <input type="text" name="working_hours_saturday" id="working_hours_saturday" value="<?php echo $contact_info['working_hours_saturday'] ?? ''; ?>" placeholder="e.g., 10:00 AM - 4:00 PM">
            </div>
        </div>
        <div class="form-group">
            <label for="working_hours_sunday">Sunday:</label>
            <input type="text" name="working_hours_sunday" id="working_hours_sunday" value="<?php echo $contact_info['working_hours_sunday'] ?? ''; ?>" placeholder="e.g., Closed">
        </div>
    </div>

    <!-- Social Media Section -->
    <div class="form-section">
        <h2>🌐 Social Media Links</h2>
        <div class="form-row">
            <div class="form-group">
                <label for="facebook_url">Facebook URL:</label>
                <input type="url" name="facebook_url" id="facebook_url" value="<?php echo $contact_info['facebook_url'] ?? ''; ?>" placeholder="https://facebook.com/yourpage">
            </div>
            <div class="form-group">
                <label for="twitter_url">Twitter URL:</label>
                <input type="url" name="twitter_url" id="twitter_url" value="<?php echo $contact_info['twitter_url'] ?? ''; ?>" placeholder="https://twitter.com/yourhandle">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="instagram_url">Instagram URL:</label>
                <input type="url" name="instagram_url" id="instagram_url" value="<?php echo $contact_info['instagram_url'] ?? ''; ?>" placeholder="https://instagram.com/yourhandle">
            </div>
            <div class="form-group">
                <label for="youtube_url">YouTube URL:</label>
                <input type="url" name="youtube_url" id="youtube_url" value="<?php echo $contact_info['youtube_url'] ?? ''; ?>" placeholder="https://youtube.com/yourchannel">
            </div>
        </div>
    </div>

    <!-- Additional Information Section -->
    <div class="form-section">
        <h2>🏢 Additional Information</h2>
        <div class="form-group">
            <label for="visit_us_text">Visit Us Text:</label>
            <textarea name="visit_us_text" id="visit_us_text" rows="3"><?php echo $contact_info['visit_us_text'] ?? ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="map_embed_url">Google Maps Embed Code:</label>
            <textarea name="map_embed_url" id="map_embed_url" rows="4" placeholder="Paste the complete Google Maps embed iframe code here"><?php echo $contact_info['map_embed_url'] ?? ''; ?></textarea>
            <small>To get the embed code: Go to Google Maps → Search your location → Click Share → Embed a map → Copy HTML</small>
        </div>
    </div>
    
    <button type="submit" class="btn-primary">Update Contact Information</button>
</form>

<div class="contact-preview">
    <h2>Preview - How it will appear on Contact Page</h2>
    
    <!-- Contact Info Cards Preview -->
    <div class="preview-cards">
        <div class="preview-card">
            <div class="card-icon">👤</div>
            <h3>Contact Person</h3>
            <p><strong><?php echo $contact_info['contact_person_name'] ?? 'Not set'; ?></strong></p>
            <p class="small-text">DoB: <?php echo $contact_info['contact_person_dob'] ?? 'Not set'; ?></p>
        </div>
        <div class="preview-card">
            <div class="card-icon">📱</div>
            <h3>Call Us</h3>
            <p><?php echo $contact_info['phone_number'] ?? 'Not set'; ?></p>
            <p class="small-text"><?php echo $contact_info['phone_availability'] ?? 'Not set'; ?></p>
        </div>
        <div class="preview-card">
            <div class="card-icon">📧</div>
            <h3>Email Us</h3>
            <p><?php echo $contact_info['email'] ?? 'Not set'; ?></p>
            <p class="small-text"><?php echo $contact_info['email_response_time'] ?? 'Not set'; ?></p>
        </div>
        <div class="preview-card">
            <div class="card-icon">📍</div>
            <h3>Visit Us</h3>
            <p><?php echo $contact_info['office_city'] ?? 'Not set'; ?></p>
            <p class="small-text"><?php echo $contact_info['office_state'] ?? 'Not set'; ?> - <?php echo $contact_info['office_pincode'] ?? 'Not set'; ?></p>
        </div>
    </div>

    <!-- Office Details Preview -->
    <div class="preview-section">
        <h3>🏢 Office Address</h3>
        <p><strong><?php echo $contact_info['office_address_line1'] ?? 'Not set'; ?></strong></p>
        <p><?php echo $contact_info['office_address_line2'] ?? 'Not set'; ?></p>
        <p><?php echo $contact_info['office_address_line3'] ?? 'Not set'; ?></p>
        <p><?php echo $contact_info['office_city'] ?? 'Not set'; ?></p>
        <p><?php echo $contact_info['office_state'] ?? 'Not set'; ?> - <?php echo $contact_info['office_pincode'] ?? 'Not set'; ?></p>
        <p><small><?php echo $contact_info['office_landmark'] ?? 'Not set'; ?></small></p>
    </div>

    <!-- Working Hours Preview -->
    <div class="preview-section">
        <h3>🕐 Working Hours</h3>
        <p><strong>Monday - Friday:</strong> <?php echo $contact_info['working_hours_weekdays'] ?? 'Not set'; ?></p>
        <p><strong>Saturday:</strong> <?php echo $contact_info['working_hours_saturday'] ?? 'Not set'; ?></p>
        <p><strong>Sunday:</strong> <?php echo $contact_info['working_hours_sunday'] ?? 'Not set'; ?></p>
    </div>

    <!-- Map Preview -->
    <?php if (!empty($contact_info['map_embed_url'])): ?>
    <div class="preview-section">
        <h3>🗺️ Find Us on Map</h3>
        <div class="map-preview">
            <?php echo $contact_info['map_embed_url']; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.contact-info-form {
    max-width: 1000px;
    margin-bottom: 40px;
}

.form-section {
    background: #f8f9fa;
    padding: 25px;
    margin-bottom: 25px;
    border-radius: 8px;
    border-left: 4px solid #e53935;
}

.form-section h2 {
    color: #e53935;
    margin-bottom: 20px;
    font-size: 18px;
    font-weight: bold;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 15px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-group textarea {
    resize: vertical;
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #666;
    font-size: 12px;
}

.btn-primary {
    background: #e53935;
    color: white;
    padding: 15px 40px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    margin-top: 20px;
}

.btn-primary:hover {
    background: #c62828;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.contact-preview {
    margin-top: 40px;
    padding-top: 40px;
    border-top: 2px solid #eee;
}

.preview-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.preview-card {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    text-align: center;
}

.preview-card .card-icon {
    font-size: 30px;
    margin-bottom: 10px;
}

.preview-card h3 {
    color: #333;
    margin-bottom: 10px;
    font-size: 16px;
}

.preview-card p {
    margin: 5px 0;
    color: #666;
}

.preview-card .small-text {
    font-size: 12px;
    color: #999;
}

.preview-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.preview-section h3 {
    color: #e53935;
    margin-bottom: 15px;
}

.preview-section p {
    margin: 5px 0;
    color: #666;
    line-height: 1.5;
}

.map-preview {
    margin-top: 15px;
}

.map-preview iframe {
    width: 100%;
    height: 300px;
    border: 0;
    border-radius: 4px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .preview-cards {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'includes/footer.php'; ?>