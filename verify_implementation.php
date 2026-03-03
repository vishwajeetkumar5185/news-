<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Implementation Verification - Live 18 India</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 15px; 
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 { 
            color: #333; 
            margin-bottom: 10px; 
            font-size: 32px;
            text-align: center;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 40px;
            font-size: 16px;
        }
        .section { 
            margin-bottom: 30px; 
            padding: 25px; 
            background: #f8f9fa; 
            border-radius: 10px;
            border-left: 5px solid #667eea;
        }
        .section h2 { 
            color: #667eea; 
            margin-bottom: 15px; 
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .check-item { 
            padding: 12px; 
            margin: 8px 0; 
            background: white; 
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .check-item strong { 
            color: #333; 
            font-size: 14px;
        }
        .status { 
            padding: 6px 15px; 
            border-radius: 20px; 
            font-weight: 600; 
            font-size: 13px;
        }
        .status.success { 
            background: #d4edda; 
            color: #155724; 
        }
        .status.warning { 
            background: #fff3cd; 
            color: #856404; 
        }
        .status.error { 
            background: #f8d7da; 
            color: #721c24; 
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-box strong {
            color: #1976D2;
        }
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            text-align: center;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        .btn-info {
            background: #17a2b8;
            color: white;
        }
        .btn-info:hover {
            background: #138496;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(23, 162, 184, 0.4);
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: #e83e8c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 Implementation Verification</h1>
        <p class="subtitle">Live 18 India - Complete System Check</p>

        <?php
        require_once 'config/database.php';
        $conn = getConnection();
        
        // Check files
        $files_to_check = [
            'includes/header.php' => 'Header with AdSense & Analytics',
            'includes/footer.php' => 'Footer with Contact Info',
            'ads.txt' => 'AdSense Verification File',
            'sitemap.xml' => 'XML Sitemap',
            'sitemap.php' => 'HTML Sitemap',
            'robots.txt' => 'Robots.txt',
            'contact.php' => 'Contact Page',
            'admin/login.php' => 'Admin Login with PIN System'
        ];
        ?>

        <!-- File Checks -->
        <div class="section">
            <h2>📁 File Verification</h2>
            <?php foreach ($files_to_check as $file => $description): ?>
                <div class="check-item">
                    <strong><?php echo $description; ?> <code><?php echo $file; ?></code></strong>
                    <?php if (file_exists($file)): ?>
                        <span class="status success">✓ Exists</span>
                    <?php else: ?>
                        <span class="status error">✗ Missing</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- AdSense Check -->
        <div class="section">
            <h2>📢 Google AdSense Integration</h2>
            <?php
            $header_content = file_get_contents('includes/header.php');
            $has_adsense = strpos($header_content, 'ca-pub-5062126875706614') !== false;
            $ads_txt_exists = file_exists('ads.txt');
            $ads_txt_content = $ads_txt_exists ? file_get_contents('ads.txt') : '';
            $ads_txt_correct = strpos($ads_txt_content, 'pub-5062126875706614') !== false;
            ?>
            <div class="check-item">
                <strong>AdSense Code in Header</strong>
                <span class="status <?php echo $has_adsense ? 'success' : 'error'; ?>">
                    <?php echo $has_adsense ? '✓ Implemented' : '✗ Missing'; ?>
                </span>
            </div>
            <div class="check-item">
                <strong>ads.txt File</strong>
                <span class="status <?php echo $ads_txt_correct ? 'success' : 'error'; ?>">
                    <?php echo $ads_txt_correct ? '✓ Configured' : '✗ Missing'; ?>
                </span>
            </div>
            <div class="info-box">
                <strong>Publisher ID:</strong> ca-pub-5062126875706614<br>
                <strong>Next Step:</strong> Go to Google AdSense dashboard and request site verification
            </div>
        </div>

        <!-- Analytics Check -->
        <div class="section">
            <h2>📊 Google Analytics Integration</h2>
            <?php
            $has_analytics = strpos($header_content, 'G-G14C56RN9E') !== false;
            $has_custom_events = strpos($header_content, 'trackNewsView') !== false;
            ?>
            <div class="check-item">
                <strong>GA4 Tracking Code</strong>
                <span class="status <?php echo $has_analytics ? 'success' : 'error'; ?>">
                    <?php echo $has_analytics ? '✓ Implemented' : '✗ Missing'; ?>
                </span>
            </div>
            <div class="check-item">
                <strong>Custom Event Tracking</strong>
                <span class="status <?php echo $has_custom_events ? 'success' : 'error'; ?>">
                    <?php echo $has_custom_events ? '✓ Implemented' : '✗ Missing'; ?>
                </span>
            </div>
            <div class="info-box">
                <strong>Tracking ID:</strong> G-G14C56RN9E<br>
                <strong>Custom Events:</strong> Article views, Video plays, Search, Social shares, Form submissions
            </div>
        </div>

        <!-- Contact Info Check -->
        <div class="section">
            <h2>📞 Contact Information</h2>
            <?php
            $contact_result = $conn->query("SELECT * FROM contact_info WHERE id = 1");
            if ($contact_result && $contact_result->num_rows > 0) {
                $contact = $contact_result->fetch_assoc();
                $phone_correct = $contact['phone_number'] == '+91 8070111786';
                $email_correct = $contact['email'] == 'live18india2020@gmail.com';
                $address_correct = $contact['office_address_line1'] == 'Office No. 003';
            ?>
                <div class="check-item">
                    <strong>Phone Number</strong>
                    <span class="status <?php echo $phone_correct ? 'success' : 'warning'; ?>">
                        <?php echo $phone_correct ? '✓ Updated' : '⚠ Needs Update'; ?>
                    </span>
                </div>
                <div class="check-item">
                    <strong>Email Address</strong>
                    <span class="status <?php echo $email_correct ? 'success' : 'warning'; ?>">
                        <?php echo $email_correct ? '✓ Updated' : '⚠ Needs Update'; ?>
                    </span>
                </div>
                <div class="check-item">
                    <strong>Office Address</strong>
                    <span class="status <?php echo $address_correct ? 'success' : 'warning'; ?>">
                        <?php echo $address_correct ? '✓ Updated' : '⚠ Needs Update'; ?>
                    </span>
                </div>
                <?php if (!$phone_correct || !$email_correct || !$address_correct): ?>
                    <div class="info-box">
                        <strong>Action Required:</strong> Database needs to be updated with new contact information.<br>
                        Click the "Update Contact Database" button below.
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="check-item">
                    <strong>Database Table</strong>
                    <span class="status error">✗ No Data Found</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- SEO Check -->
        <div class="section">
            <h2>🔍 SEO Implementation</h2>
            <?php
            $pages_with_metadata = [
                'index.php', 'about.php', 'contact.php', 'latest.php', 
                'videos.php', 'search.php', 'category.php', 'single.php',
                'privacy-policy.php', 'terms-of-use.php', 'cookie-policy.php', 'sitemap.php'
            ];
            $metadata_count = 0;
            foreach ($pages_with_metadata as $page) {
                if (file_exists($page)) {
                    $content = file_get_contents($page);
                    if (strpos($content, 'pageTitle') !== false || strpos($content, 'meta name="description"') !== false) {
                        $metadata_count++;
                    }
                }
            }
            ?>
            <div class="check-item">
                <strong>Pages with Metadata</strong>
                <span class="status success"><?php echo $metadata_count; ?> / <?php echo count($pages_with_metadata); ?> Pages</span>
            </div>
            <div class="check-item">
                <strong>XML Sitemap</strong>
                <span class="status <?php echo file_exists('sitemap.xml') ? 'success' : 'error'; ?>">
                    <?php echo file_exists('sitemap.xml') ? '✓ Generated' : '✗ Missing'; ?>
                </span>
            </div>
            <div class="check-item">
                <strong>Robots.txt</strong>
                <span class="status <?php echo file_exists('robots.txt') ? 'success' : 'error'; ?>">
                    <?php echo file_exists('robots.txt') ? '✓ Configured' : '✗ Missing'; ?>
                </span>
            </div>
        </div>

        <!-- Admin System Check -->
        <div class="section">
            <h2>🔐 Admin System</h2>
            <?php
            $admin_result = $conn->query("SHOW COLUMNS FROM admin_users LIKE 'pin'");
            $has_pin_column = $admin_result && $admin_result->num_rows > 0;
            ?>
            <div class="check-item">
                <strong>PIN System Database</strong>
                <span class="status <?php echo $has_pin_column ? 'success' : 'warning'; ?>">
                    <?php echo $has_pin_column ? '✓ Configured' : '⚠ Needs Setup'; ?>
                </span>
            </div>
            <div class="check-item">
                <strong>Admin Login Page</strong>
                <span class="status <?php echo file_exists('admin/login.php') ? 'success' : 'error'; ?>">
                    <?php echo file_exists('admin/login.php') ? '✓ Available' : '✗ Missing'; ?>
                </span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="check_contact_info.php" class="btn btn-info">Check Contact Info</a>
            <a href="update_contact_database.php" class="btn btn-success">Update Contact Database</a>
            <a href="index.php" class="btn btn-primary">Go to Homepage</a>
            <a href="admin/login.php" class="btn btn-primary">Admin Login</a>
        </div>

        <div class="info-box" style="margin-top: 30px; text-align: center;">
            <strong>📋 Documentation:</strong> See <code>ADSENSE_IMPLEMENTATION.md</code> for complete implementation details
        </div>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
