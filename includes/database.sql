
CREATE DATABASE IF NOT EXISTS bihak;
USE bihak;

CREATE TABLE IF NOT EXISTS usagers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    age INT,
    description TEXT,
    username VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    video_link TEXT,
    category TEXT,
    request TEXT,
    contacts TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
