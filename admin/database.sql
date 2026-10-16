CREATE DATABASE IF NOT EXISTS elegance_salon;
USE elegance_salon;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS activity_logs;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS feedback;
DROP TABLE IF EXISTS inventory;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin', 'receptionist', 'stylist') DEFAULT 'stylist',
    is_active INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE clients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    service_name VARCHAR(100) NOT NULL,
    description TEXT,
    duration INT DEFAULT 30,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50),
    is_active INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT,
    service_id INT,
    stylist_id INT,
    appointment_date DATE,
    appointment_time TIME,
    status VARCHAR(20) DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL,
    FOREIGN KEY (stylist_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE inventory (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    quantity INT DEFAULT 0,
    unit_price DECIMAL(10,2),
    supplier_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_no VARCHAR(50) UNIQUE,
    client_id INT,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'card', 'online') DEFAULT 'cash',
    payment_status ENUM('pending', 'completed') DEFAULT 'pending',
    payment_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL
);

CREATE TABLE feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    rating INT,
    message TEXT NOT NULL,
    reply TEXT,
    is_read INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    subject VARCHAR(200),
    message TEXT,
    is_read INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100),
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

INSERT INTO users (username, email, password, full_name, phone, role) VALUES
('admin', 'admin@salon.com', '123456', 'Administrator', '9876543201', 'admin'),
('receptionist', 'reception@salon.com', '123456', 'Receptionist Raj', '9876543202', 'receptionist'),
('stylist', 'stylist@salon.com', '123456', 'Sarah Johnson', '9876543203', 'stylist');

INSERT INTO clients (name, email, phone, address) VALUES
('Amit Sharma', 'amit@email.com', '9876543210', 'Delhi'),
('Priya Patel', 'priya@email.com', '9876543211', 'Mumbai'),
('Rahul Mehta', 'rahul@email.com', '9876543212', 'Kolkata');

INSERT INTO services (service_name, price, duration, category) VALUES
('Haircut - Women', 50.00, 60, 'Hair'),
('Haircut - Men', 35.00, 30, 'Hair'),
('Manicure', 40.00, 45, 'Nails'),
('Pedicure', 55.00, 60, 'Nails'),
('Facial - Basic', 60.00, 45, 'Facial');

INSERT INTO appointments (client_id, service_id, stylist_id, appointment_date, appointment_time, status) VALUES
(1, 1, 3, CURDATE(), '10:00:00', 'confirmed'),
(2, 3, 3, CURDATE(), '11:30:00', 'confirmed');

INSERT INTO inventory (product_name, category, quantity, unit_price, supplier_name) VALUES
('Professional Shampoo', 'Hair Products', 25, 15.99, 'Beauty Supplies Co.'),
('Hair Color - Brown', 'Hair Products', 8, 12.50, 'Color Pro'),
('Nail Polish Kit', 'Nail Products', 15, 25.00, 'Nail Art Supply');

INSERT INTO payments (invoice_no, client_id, amount, payment_method, payment_status, payment_date) VALUES
('INV2024001', 1, 50.00, 'cash', 'completed', CURDATE()),
('INV2024002', 2, 40.00, 'card', 'completed', CURDATE());

INSERT INTO feedback (name, email, rating, message) VALUES
('Amit Sharma', 'amit@email.com', 5, 'Excellent service! Very professional.'),
('Priya Patel', 'priya@email.com', 4, 'Good experience, will come again.');

SELECT 'DATABASE READY' AS Status;
SELECT COUNT(*) AS Users FROM users;
SELECT COUNT(*) AS Clients FROM clients;
SELECT COUNT(*) AS Services FROM services;
SELECT COUNT(*) AS Appointments FROM appointments;
SELECT COUNT(*) AS Inventory FROM inventory;
SELECT COUNT(*) AS Payments FROM payments;
SELECT COUNT(*) AS Feedback FROM feedback;