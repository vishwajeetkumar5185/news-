<?php 
include 'includes/header.php';

// Get dynamic contact information
$contact_info = $conn->query("SELECT * FROM contact_info WHERE id = 1")->fetch_assoc();

$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $subject = clean($_POST['subject']);
    $message = clean($_POST['message']);
    
    if ($name && $email && $subject && $message) {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        $fullMessage = "Subject: " . $subject . "\n\n" . $message;
        $stmt->bind_param("sss", $name, $email, $fullMessage);
        
        if ($stmt->execute()) {
            $success = "Thank you for contacting us! We'll get back to you soon.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    } else {
        $error = "Please fill all fields.";
    }
}
?>

<div class="contact-page-modern">
    <div class="container">
        <!-- Contact Info Cards -->
        <div class="contact-info-grid">
            <div class="info-card-modern">
                <div class="card-icon">👤</div>
                <h3>Contact Person</h3>
                <p><strong><?php echo htmlspecialchars($contact_info['contact_person_name'] ?? 'Rakesh Rajendra Singh'); ?></strong></p>
                <p class="small-text">DoB: <?php echo htmlspecialchars($contact_info['contact_person_dob'] ?? '16-01-1990'); ?></p>
            </div>
            <div class="info-card-modern">
                <div class="card-icon">📱</div>
                <h3>Call Us</h3>
                <p><a href="tel:<?php echo str_replace([' ', '-', '(', ')'], '', $contact_info['phone_number'] ?? '9619501369'); ?>"><?php echo htmlspecialchars($contact_info['phone_number'] ?? '+91 9619501369'); ?></a></p>
                <p class="small-text"><?php echo htmlspecialchars($contact_info['phone_availability'] ?? 'Mon-Sun, 24/7 Available'); ?></p>
            </div>
            <div class="info-card-modern">
                <div class="card-icon">📧</div>
                <h3>Email Us</h3>
                <p><a href="mailto:<?php echo htmlspecialchars($contact_info['email'] ?? 'contact@live18india.com'); ?>"><?php echo htmlspecialchars($contact_info['email'] ?? 'contact@live18india.com'); ?></a></p>
                <p class="small-text"><?php echo htmlspecialchars($contact_info['email_response_time'] ?? 'We\'ll reply within 24 hours'); ?></p>
            </div>
            <div class="info-card-modern">
                <div class="card-icon">📍</div>
                <h3>Visit Us</h3>
                <p><?php echo htmlspecialchars($contact_info['office_city'] ?? 'Mira Road East, Mumbai'); ?></p>
                <p class="small-text"><?php echo htmlspecialchars($contact_info['office_state'] ?? 'Maharashtra'); ?> - <?php echo htmlspecialchars($contact_info['office_pincode'] ?? '401107'); ?></p>
            </div>
        </div>

        <!-- Main Content Section -->
        <div class="contact-main-section">
            <!-- Contact Form -->
            <div class="contact-form-wrapper">
                <div class="form-header">
                    <h2>Send Us a Message</h2>
                    <p>Fill out the form below and our team will get back to you shortly</p>
                </div>

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm-2 15l-5-5 1.41-1.41L8 12.17l7.59-7.59L17 6l-9 9z"/></svg>
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm1 15H9v-2h2v2zm0-4H9V5h2v6z"/></svg>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="modern-contact-form">
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="name">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                Full Name
                            </label>
                            <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                        </div>
                        <div class="form-field">
                            <label for="email">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                                Email Address
                            </label>
                            <input type="email" id="email" name="email" placeholder="your@email.com" required>
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="subject">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg>
                            Subject
                        </label>
                        <input type="text" id="subject" name="subject" placeholder="What is this regarding?" required>
                    </div>

                    <div class="form-field">
                        <label for="message">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
                            Your Message
                        </label>
                        <textarea id="message" name="message" rows="6" placeholder="Tell us more about your inquiry..." required></textarea>
                    </div>

                    <button type="submit" class="submit-button">
                        <span>Send Message</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                    </button>
                </form>
            </div>

            <!-- Contact Details Sidebar -->
            <div class="contact-details-sidebar">
                <!-- Office Details -->
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div class="header-icon">🏢</div>
                        <h3>Office Address</h3>
                    </div>
                    <div class="detail-card-body">
                        <p><strong><?php echo htmlspecialchars($contact_info['office_address_line1'] ?? 'Flat 302, Venkatesh Appartment'); ?></strong></p>
                        <p><?php echo htmlspecialchars($contact_info['office_address_line2'] ?? 'Near Raval Nagar'); ?></p>
                        <p><?php echo htmlspecialchars($contact_info['office_address_line3'] ?? 'Behind Station Road'); ?></p>
                        <p><?php echo htmlspecialchars($contact_info['office_city'] ?? 'Mira Road East, Mumbai'); ?></p>
                        <p><?php echo htmlspecialchars($contact_info['office_state'] ?? 'Maharashtra'); ?> - <?php echo htmlspecialchars($contact_info['office_pincode'] ?? '401107'); ?></p>
                        <div class="landmark">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                            <span><?php echo htmlspecialchars($contact_info['office_landmark'] ?? 'Landmark: Mira Road Station Bhaji Market'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Working Hours -->
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div class="header-icon">🕐</div>
                        <h3>Working Hours</h3>
                    </div>
                    <div class="detail-card-body">
                        <div class="hours-row">
                            <span>Monday - Friday</span>
                            <strong><?php echo htmlspecialchars($contact_info['working_hours_weekdays'] ?? '9:00 AM - 6:00 PM'); ?></strong>
                        </div>
                        <div class="hours-row">
                            <span>Saturday</span>
                            <strong><?php echo htmlspecialchars($contact_info['working_hours_saturday'] ?? '10:00 AM - 4:00 PM'); ?></strong>
                        </div>
                        <div class="hours-row">
                            <span>Sunday</span>
                            <strong><?php echo htmlspecialchars($contact_info['working_hours_sunday'] ?? 'Closed'); ?></strong>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div class="header-icon">🌐</div>
                        <h3>Connect With Us</h3>
                    </div>
                    <div class="detail-card-body">
                        <div class="social-buttons">
                            <a href="<?php echo htmlspecialchars($contact_info['facebook_url'] ?? '#'); ?>" class="social-btn facebook">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/></svg>
                                Facebook
                            </a>
                            <a href="<?php echo htmlspecialchars($contact_info['twitter_url'] ?? '#'); ?>" class="social-btn twitter">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M22.46 6c-.85.38-1.78.64-2.75.76 1-.6 1.76-1.55 2.12-2.68-.93.55-1.96.95-3.06 1.17-.88-.94-2.13-1.53-3.51-1.53-2.66 0-4.81 2.16-4.81 4.81 0 .38.04.75.13 1.1-4-.2-7.54-2.12-9.91-5.04-.42.72-.66 1.55-.66 2.44 0 1.67.85 3.14 2.14 4-.79-.03-1.53-.24-2.18-.6v.06c0 2.33 1.66 4.28 3.86 4.72-.4.11-.83.17-1.27.17-.31 0-.62-.03-.92-.08.62 1.94 2.42 3.35 4.55 3.39-1.67 1.31-3.77 2.09-6.05 2.09-.39 0-.78-.02-1.17-.07 2.18 1.4 4.77 2.21 7.55 2.21 9.06 0 14.01-7.5 14.01-14.01 0-.21 0-.42-.02-.63.96-.69 1.8-1.56 2.46-2.55z"/></svg>
                                Twitter
                            </a>
                            <a href="<?php echo htmlspecialchars($contact_info['instagram_url'] ?? '#'); ?>" class="social-btn instagram">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4c0 3.2-2.6 5.8-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8C2 4.6 4.6 2 7.8 2m-.2 2C5.6 4 4 5.6 4 7.6v8.8C4 18.4 5.6 20 7.6 20h8.8c2 0 3.6-1.6 3.6-3.6V7.6C20 5.6 18.4 4 16.4 4H7.6m9.65 1.5c.69 0 1.25.56 1.25 1.25s-.56 1.25-1.25 1.25-1.25-.56-1.25-1.25.56-1.25 1.25-1.25M12 7c2.76 0 5 2.24 5 5s-2.24 5-5 5-5-2.24-5-5 2.24-5 5-5m0 2c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                Instagram
                            </a>
                            <a href="<?php echo htmlspecialchars($contact_info['youtube_url'] ?? '#'); ?>" class="social-btn youtube">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M10 15l5.19-3L10 9v6m11.56-7.83c.13.47.22 1.1.28 1.9.07.8.1 1.49.1 2.09L22 12c0 2.19-.16 3.8-.44 4.83-.25.9-.83 1.48-1.73 1.73-.47.13-1.33.22-2.65.28-1.3.07-2.49.1-3.59.1L12 19c-4.19 0-6.8-.16-7.83-.44-.9-.25-1.48-.83-1.73-1.73-.13-.47-.22-1.1-.28-1.9-.07-.8-.1-1.49-.1-2.09L2 12c0-2.19.16-3.8.44-4.83.25-.9.83-1.48 1.73-1.73.47-.13 1.33-.22 2.65-.28 1.3-.07 2.49-.1 3.59-.1L12 5c4.19 0 6.8.16 7.83.44.9.25 1.48.83 1.73 1.73z"/></svg>
                                YouTube
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="map-section">
            <h2>Find Us on Map</h2>
            <div class="map-container">
                <?php echo $contact_info['map_embed_url'] ?? '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3766.8!2d72.8577!3d19.2812!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b0e57647e5e5%3A0x1234567890abcdef!2sMira%20Road%20East%2C%20Mumbai%2C%20Maharashtra%20401107!5e0!3m2!1sen!2sin!4v1234567890" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
