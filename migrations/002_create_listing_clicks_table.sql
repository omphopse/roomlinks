CREATE TABLE IF NOT EXISTS listing_clicks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NOT NULL,
    listing_owner_id INT NOT NULL,
    user_id INT DEFAULT NULL,
    user_name VARCHAR(191) DEFAULT NULL,
    user_email VARCHAR(191) DEFAULT NULL,
    user_ip VARCHAR(45) DEFAULT NULL,
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_listing_owner_id (listing_owner_id),
    INDEX idx_listing_id (listing_id),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
