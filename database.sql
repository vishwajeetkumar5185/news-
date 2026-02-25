CREATE DATABASE IF NOT EXISTS news_portal;
USE news_portal;

CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    logo VARCHAR(255),
    site_name VARCHAR(100),
    breaking_news TEXT,
    footer_text TEXT,
    about_content TEXT,
    live_video_url VARCHAR(500)
);

CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    slug VARCHAR(100) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE banners (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    image VARCHAR(255),
    link VARCHAR(255),  
    position VARCHAR(50),
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE news (
    id INT PRIMARY KEY AUTO_INCREMENT,Samajh gaya! Footer mein bhi wahi categories honge jo header mein hain, aur background white aur red theme mein hoga.
    
    
    title VARCHAR(255),
    subtitle VARCHAR(255),
    content TEXT,
    image VARCHAR(255),
    video_file VARCHAR(255),
    youtube_url VARCHAR(500),
    category_id INT,
    featured TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE videos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    youtube_url VARCHAR(500),
    video_file VARCHAR(255),
    thumbnail VARCHAR(255),
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (password: admin123)
INSERT INTO admin_users (username, password) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert default settings
INSERT INTO settings (site_name, breaking_news, footer_text, about_content) 
VALUES ('News Portal', 'Welcome to our news portal!', '© 2024 News Portal. All rights reserved.', 'About our news portal...');
