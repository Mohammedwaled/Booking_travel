-- Database schema for Booking Travel (localhost)

CREATE DATABASE IF NOT EXISTS booking_travel_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE booking_travel_db;

CREATE TABLE IF NOT EXISTS cities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  details TEXT,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  agent_name VARCHAR(150) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','agent','admin') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Packages table
CREATE TABLE IF NOT EXISTS packages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  agent_id INT NOT NULL,
  city_id INT NOT NULL,
  title VARCHAR(200) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  details TEXT,
  status ENUM('pending','approved','rejected') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (agent_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  package_id INT NOT NULL,
  user_id INT DEFAULT NULL,
  customer_name VARCHAR(150) NOT NULL,
  customer_phone VARCHAR(30) NOT NULL,
  price DECIMAL(10,2) DEFAULT 0,
  status VARCHAR(50) DEFAULT 'قيد الانتظار',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Seed an admin (email: admin@example.com, password: admin123)
INSERT IGNORE INTO users (full_name, email, password, role)
VALUES (
  'Super Admin',
  'admin@example.com',
  '$2y$10$NjoCLr8tWVCya5CttKpNXO8ZnnOsMpXneKP6Tui42zetDEZOS16.O',
  'admin'
);

-- Contacts table
CREATE TABLE IF NOT EXISTS contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  subject VARCHAR(200) DEFAULT NULL,
  message TEXT NOT NULL,
  status ENUM('new','read','replied') DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed a sample city
INSERT IGNORE INTO cities (id, title, details, price, agent_name)
VALUES (1, 'القاهرة', 'باقة القاهرة الكاملة مع جولات المعز وخان الخليلي والقلعة والنيل', 1500, 'وكيل القاهرة');

