    <footer>
        <!-- Footer Main Section -->
        <div class="footer-main">
            <div class="container">
                <div class="footer-content-wrapper">
                    <!-- Left Section: Brand & Social -->
                    <div class="footer-brand-section">
                        <div class="footer-logo-large">
                            <?php if (!empty($settings['logo'])): ?>
                                <img src="uploads/<?php echo $settings['logo']; ?>" alt="Logo">
                            <?php else: ?>
                                <div class="logo-text">
                                    <span class="logo-news">LIVE</span><span class="logo-number">18</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <p class="footer-description">India's Leading News Portal - Stay Updated, Stay Informed</p>
                        
                        <div class="footer-social-section">
                            <h5>Follow Us</h5>
                            <div class="footer-social-icons">
                                <a href="#" class="social-icon facebook" title="Facebook">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/></svg>
                                </a>
                                <a href="#" class="social-icon twitter" title="Twitter">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22.46 6c-.85.38-1.78.64-2.75.76 1-.6 1.76-1.55 2.12-2.68-.93.55-1.96.95-3.06 1.17-.88-.94-2.13-1.53-3.51-1.53-2.66 0-4.81 2.16-4.81 4.81 0 .38.04.75.13 1.1-4-.2-7.54-2.12-9.91-5.04-.42.72-.66 1.55-.66 2.44 0 1.67.85 3.14 2.14 4-.79-.03-1.53-.24-2.18-.6v.06c0 2.33 1.66 4.28 3.86 4.72-.4.11-.83.17-1.27.17-.31 0-.62-.03-.92-.08.62 1.94 2.42 3.35 4.55 3.39-1.67 1.31-3.77 2.09-6.05 2.09-.39 0-.78-.02-1.17-.07 2.18 1.4 4.77 2.21 7.55 2.21 9.06 0 14.01-7.5 14.01-14.01 0-.21 0-.42-.02-.63.96-.69 1.8-1.56 2.46-2.55z"/></svg>
                                </a>
                                <a href="#" class="social-icon instagram" title="Instagram">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4c0 3.2-2.6 5.8-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8C2 4.6 4.6 2 7.8 2m-.2 2C5.6 4 4 5.6 4 7.6v8.8C4 18.4 5.6 20 7.6 20h8.8c2 0 3.6-1.6 3.6-3.6V7.6C20 5.6 18.4 4 16.4 4H7.6m9.65 1.5c.69 0 1.25.56 1.25 1.25s-.56 1.25-1.25 1.25-1.25-.56-1.25-1.25.56-1.25 1.25-1.25M12 7c2.76 0 5 2.24 5 5s-2.24 5-5 5-5-2.24-5-5 2.24-5 5-5m0 2c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                </a>
                                <a href="#" class="social-icon youtube" title="YouTube">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M10 15l5.19-3L10 9v6m11.56-7.83c.13.47.22 1.1.28 1.9.07.8.1 1.49.1 2.09L22 12c0 2.19-.16 3.8-.44 4.83-.25.9-.83 1.48-1.73 1.73-.47.13-1.33.22-2.65.28-1.3.07-2.49.1-3.59.1L12 19c-4.19 0-6.8-.16-7.83-.44-.9-.25-1.48-.83-1.73-1.73-.13-.47-.22-1.1-.28-1.9-.07-.8-.1-1.49-.1-2.09L2 12c0-2.19.16-3.8.44-4.83.25-.9.83-1.48 1.73-1.73.47-.13 1.33-.22 2.65-.28 1.3-.07 2.49-.1 3.59-.1L12 5c4.19 0 6.8.16 7.83.44.9.25 1.48.83 1.73 1.73z"/></svg>
                                </a>
                                <a href="#" class="social-icon whatsapp" title="WhatsApp">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Middle Section: Quick Links -->
                    <div class="footer-links-grid">
                        <div class="footer-link-column">
                            <h4>Quick Links</h4>
                            <ul>
                                <li><a href="videos.php">Videos</a></li>
                                <li><a href="about.php">About Us</a></li>
                                <li><a href="contact.php">Contact Us</a></li>
                                <li><a href="#">Careers</a></li>
                                <li><a href="#">Advertise</a></li>
                            </ul>
                        </div>
                        
                        <div class="footer-link-column">
                            <h4>Legal</h4>
                            <ul>
                                <li><a href="privacy-policy.php">Privacy Policy</a></li>
                                <li><a href="terms-of-use.php">Terms of Use</a></li>
                                <li><a href="cookie-policy.php">Cookie Policy</a></li>
                                <li><a href="sitemap.php">Sitemap</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Right Section: Contact Card -->
                    <div class="footer-contact-card">
                        <div class="contact-card-header">
                            <span class="contact-icon">📞</span>
                            <h4>Get In Touch</h4>
                        </div>
                        <div class="contact-details">
                            <div class="contact-item">
                                <span class="icon">👤</span>
                                <div class="info">
                                    <strong>Rakesh Rajendra Singh</strong>
                                    <small>DoB: 16-01-1990</small>
                                </div>
                            </div>
                            <div class="contact-item">
                                <span class="icon">📱</span>
                                <div class="info">
                                    <a href="tel:9619501369">+91 9619501369</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <span class="icon">📍</span>
                                <div class="info">
                                    <p>Flat 302, Venkatesh Appartment<br>
                                    Near Raval Nagar, Behind Station Road<br>
                                    Mira Road East, Mumbai<br>
                                    Maharashtra - 401107</p>
                                    <small>Landmark: Mira Road Station Bhaji Market</small>
                                </div>
                            </div>
                        </div>
                        <div class="footer-map-embed">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3766.8!2d72.8577!3d19.2812!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b0e57647e5e5%3A0x1234567890abcdef!2sMira%20Road%20East%2C%20Mumbai%2C%20Maharashtra%20401107!5e0!3m2!1sen!2sin!4v1234567890" width="100%" height="180" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-flex">
                    <p class="copyright">© 2024 Live 18 India. All Rights Reserved.</p>
                    <p class="disclaimer">Disclaimer: Live18India.com is part of Network18 Media & Investments Limited.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <script>
    // Sticky header on scroll
    window.addEventListener('scroll', function() {
        const mainHeader = document.querySelector('.main-header');
        const body = document.body;
        
        if (window.scrollY > 100) {
            mainHeader.classList.add('sticky');
            body.classList.add('header-sticky');
        } else {
            mainHeader.classList.remove('sticky');
            body.classList.remove('header-sticky');
        }
    });
    </script>
</body>
</html>
<?php $conn->close(); ?>

    
    <!-- Video Popup Modal -->
    <div id="videoModal" class="video-modal">
        <div class="video-modal-content">
            <span class="video-close" onclick="closeVideoPopup()">&times;</span>
            <div id="videoContainer"></div>
        </div>
    </div>
    
    <script>
    function openVideoPopup(videoSource, type) {
        const modal = document.getElementById('videoModal');
        const container = document.getElementById('videoContainer');
        
        if (type === 'youtube') {
            container.innerHTML = '<iframe width="100%" height="500" src="https://www.youtube.com/embed/' + videoSource + '?autoplay=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
        } else {
            container.innerHTML = '<video width="100%" height="500" controls autoplay><source src="' + videoSource + '" type="video/mp4">Your browser does not support the video tag.</video>';
        }
        
        modal.style.display = 'block';
    }
    
    function closeVideoPopup() {
        const modal = document.getElementById('videoModal');
        const container = document.getElementById('videoContainer');
        container.innerHTML = '';
        modal.style.display = 'none';
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('videoModal');
        if (event.target == modal) {
            closeVideoPopup();
        }
    }
    </script>
