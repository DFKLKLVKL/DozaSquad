-- Создание базы данных
CREATE DATABASE IF NOT EXISTS dozasquad_db;
USE dozasquad_db;

-- Создание таблицы для контактов
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_created_at (created_at)
);

-- Создание таблицы для просмотра сообщений (админ-панель)
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Создание администратора по умолчанию (пароль: admin123)
INSERT INTO admin_users (username, password) 
VALUES ('admin', '$2y$10$YourHashedPasswordHere');

-- Для просмотра всех сообщений можно использовать этот запрос:
SELECT * FROM contacts ORDER BY created_at DESC;