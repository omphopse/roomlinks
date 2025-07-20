CREATE TABLE profile_pictures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gender ENUM('men', 'women') NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
