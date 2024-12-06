-- Create database
CREATE DATABASE IF NOT EXISTS inventory_system;
USE inventory_system;

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    quantity INT NOT NULL DEFAULT 0,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create activity_log table
CREATE TABLE IF NOT EXISTS activity_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample categories
INSERT INTO categories (name, description) VALUES
('Electronics', 'Electronic devices and accessories'),
('Furniture', 'Home and office furniture'),
('Stationery', 'Office supplies and stationery items'),
('Books', 'Books and publications'),
('Tools', 'Hardware and tools');

-- Insert sample products
INSERT INTO products (name, description, quantity, price, category) VALUES
('Laptop', 'High-performance laptop', 10, 999.99, 'Electronics'),
('Office Chair', 'Ergonomic office chair', 20, 199.99, 'Furniture'),
('Notebooks', 'Pack of 5 notebooks', 100, 9.99, 'Stationery'),
('Wireless Mouse', 'Bluetooth wireless mouse', 15, 29.99, 'Electronics'),
('Desk Lamp', 'LED desk lamp', 25, 39.99, 'Electronics'),
('Bookshelf', 'Wooden bookshelf', 5, 149.99, 'Furniture'),
('Printer Paper', 'A4 printer paper (500 sheets)', 50, 4.99, 'Stationery'),
('Hammer', 'Steel claw hammer', 30, 19.99, 'Tools'),
('Programming Book', 'Learn PHP Programming', 12, 49.99, 'Books');