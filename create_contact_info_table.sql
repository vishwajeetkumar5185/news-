-- First, drop the existing table if it exists to avoid conflicts
DROP TABLE IF EXISTS `contact_info`;

-- Create the new contact_info table with all required fields
CREATE TABLE `contact_info` (
  `id` int(11) NOT NULL PRIMARY KEY,
  `contact_person_name` varchar(100) NOT NULL,
  `contact_person_dob` varchar(20),
  `phone_number` varchar(20) NOT NULL,
  `phone_availability` varchar(100),
  `email` varchar(100) NOT NULL,
  `email_response_time` varchar(100),
  `office_address_line1` varchar(200),
  `office_address_line2` varchar(200),
  `office_address_line3` varchar(200),
  `office_city` varchar(100),
  `office_state` varchar(100),
  `office_pincode` varchar(10),
  `office_landmark` varchar(200),
  `working_hours_weekdays` varchar(100),
  `working_hours_saturday` varchar(100),
  `working_hours_sunday` varchar(100),
  `facebook_url` varchar(200),
  `twitter_url` varchar(200),
  `instagram_url` varchar(200),
  `youtube_url` varchar(200),
  `map_embed_url` text,
  `visit_us_text` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default contact information
INSERT INTO `contact_info` (`id`, `contact_person_name`, `contact_person_dob`, `phone_number`, `phone_availability`, `email`, `email_response_time`, `office_address_line1`, `office_address_line2`, `office_address_line3`, `office_city`, `office_state`, `office_pincode`, `office_landmark`, `working_hours_weekdays`, `working_hours_saturday`, `working_hours_sunday`, `facebook_url`, `twitter_url`, `instagram_url`, `youtube_url`, `map_embed_url`, `visit_us_text`) VALUES
(1, 'Rakesh Rajendra Singh', '16-01-1990', '+91 9619501369', 'Mon-Sun, 24/7 Available', 'contact@live18india.com', 'We will reply within 24 hours', 'Flat 302, Venkatesh Appartment', 'Near Raval Nagar', 'Behind Station Road', 'Mira Road East, Mumbai', 'Maharashtra', '401107', 'Landmark: Mira Road Station Bhaji Market', '9:00 AM - 6:00 PM', '10:00 AM - 4:00 PM', 'Closed', '#', '#', '#', '#', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3766.8!2d72.8577!3d19.2812!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b0e57647e5e5%3A0x1234567890abcdef!2sMira%20Road%20East%2C%20Mumbai%2C%20Maharashtra%20401107!5e0!3m2!1sen!2sin!4v1234567890" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>', 'Visit our office during business hours for any inquiries or support.');