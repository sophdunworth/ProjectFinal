CREATE TABLE reviews (
    reviewID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    service VARCHAR(100) NOT NULL,
    reviewText VARCHAR(300) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (userID) REFERENCES users(userID)
);

