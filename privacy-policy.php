<?php
require_once 'config/database.php';
require_once 'config/functions.php';

// SEO Meta Data for Privacy Policy
$pageTitle = "Privacy Policy - Live 18 India | Data Protection & User Privacy";
$pageDescription = "Read Live 18 India's Privacy Policy to understand how we collect, use, and protect your personal information. Learn about our commitment to user privacy and data security.";
$pageKeywords = "privacy policy, data protection, user privacy, Live 18 India privacy, personal information, data security, cookies policy";
$canonicalUrl = "https://live18india.com/privacy-policy.php";

// Open Graph Meta Tags
$ogTitle = "Privacy Policy - Live 18 India";
$ogDescription = "Learn about Live 18 India's commitment to protecting your privacy and personal information. Read our comprehensive privacy policy.";
$ogImage = "https://live18india.com/assets/images/privacy-policy.jpg";
$ogUrl = $canonicalUrl;
$ogType = "website";

// Twitter Card Meta Tags
$twitterTitle = $ogTitle;
$twitterDescription = $ogDescription;
$twitterImage = $ogImage;

// Schema.org JSON-LD for Privacy Policy
$schemaData = [
    "@context" => "https://schema.org",
    "@type" => "WebPage",
    "name" => "Privacy Policy",
    "description" => $pageDescription,
    "url" => $canonicalUrl,
    "publisher" => [
        "@type" => "NewsMediaOrganization",
        "name" => "Live 18 India",
        "url" => "https://live18india.com"
    ],
    "dateModified" => date('c'),
    "inLanguage" => "en-IN"
];

// Set page category for analytics
$pageCategory = 'privacy_policy';

include 'includes/header.php';
?>

<!-- Schema.org JSON-LD for Privacy Policy -->
<script type="application/ld+json">
<?php echo json_encode($schemaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
</script>

<div class="legal-page">
    <div class="container">
        <div class="legal-header">
            <h1>Privacy Policy</h1>
            <p class="last-updated">Last Updated: February 25, 2026</p>
        </div>

        <div class="legal-content">
            <section class="legal-section">
                <h2>1. Information We Collect</h2>
                <p>We collect information that you provide directly to us, including:</p>
                <ul>
                    <li>Name and contact information</li>
                    <li>Email address</li>
                    <li>Phone number</li>
                    <li>Comments and feedback</li>
                    <li>Newsletter subscription preferences</li>
                </ul>
            </section>

            <section class="legal-section">
                <h2>2. How We Use Your Information</h2>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Provide, maintain, and improve our services</li>
                    <li>Send you newsletters and updates</li>
                    <li>Respond to your comments and questions</li>
                    <li>Analyze usage patterns and trends</li>
                    <li>Protect against fraudulent or illegal activity</li>
                </ul>
            </section>

            <section class="legal-section">
                <h2>3. Information Sharing</h2>
                <p>We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:</p>
                <ul>
                    <li>With your consent</li>
                    <li>To comply with legal obligations</li>
                    <li>To protect our rights and safety</li>
                    <li>With service providers who assist our operations</li>
                </ul>
            </section>

            <section class="legal-section">
                <h2>4. Cookies and Tracking</h2>
                <p>We use cookies and similar tracking technologies to enhance your experience. You can control cookies through your browser settings. For more details, please see our <a href="cookie-policy.php">Cookie Policy</a>.</p>
            </section>

            <section class="legal-section">
                <h2>5. Data Security</h2>
                <p>We implement appropriate security measures to protect your personal information. However, no method of transmission over the internet is 100% secure.</p>
            </section>

            <section class="legal-section">
                <h2>6. Your Rights</h2>
                <p>You have the right to:</p>
                <ul>
                    <li>Access your personal information</li>
                    <li>Correct inaccurate data</li>
                    <li>Request deletion of your data</li>
                    <li>Opt-out of marketing communications</li>
                    <li>Lodge a complaint with authorities</li>
                </ul>
            </section>

            <section class="legal-section">
                <h2>7. Children's Privacy</h2>
                <p>Our services are not directed to children under 13. We do not knowingly collect personal information from children under 13.</p>
            </section>

            <section class="legal-section">
                <h2>8. Changes to This Policy</h2>
                <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page.</p>
            </section>

            <section class="legal-section">
                <h2>9. Contact Us</h2>
                <p>If you have questions about this Privacy Policy, please contact us:</p>
                <div class="contact-info">
                    <p><strong>Email:</strong> privacy@live18india.com</p>
                    <p><strong>Phone:</strong> +91 9619501369</p>
                    <p><strong>Address:</strong> Flat 302, Venkatesh Appartment, Mira Road East, Mumbai, Maharashtra - 401107</p>
                </div>
            </section>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
