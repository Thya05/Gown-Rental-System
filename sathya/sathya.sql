-- Create and use database
CREATE DATABASE IF NOT EXISTS gown_rental;
USE gown_rental;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    role TINYINT DEFAULT 0 -- 0=customer,    1=admin
);

-- Table for Gowns
CREATE TABLE gowns (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    gown_name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 500.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Contact Form
CREATE TABLE IF NOT EXISTS contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Bookings
CREATE TABLE IF NOT EXISTS booking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    gown_id INT NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    return_date DATE NOT NULL,
    return_time TIME NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    payment_status ENUM('unpaid', 'partial', 'paid') DEFAULT 'unpaid',
    payment_amount DECIMAL(10,2) DEFAULT 0.00,
    payment_reference VARCHAR(50),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (gown_id) REFERENCES gowns(id)
);

-- Table for Appointment History
CREATE TABLE IF NOT EXISTS appointment_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    status_from ENUM('pending', 'confirmed', 'cancelled', 'completed'),
    status_to ENUM('pending', 'confirmed', 'cancelled', 'completed'),
    changed_by INT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES booking(id),
    FOREIGN KEY (changed_by) REFERENCES users(id)
);

-- Table for Admin Dashboard Stats
CREATE TABLE IF NOT EXISTS admin_dashboard_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_users INT DEFAULT 0,
    total_appointments INT DEFAULT 0,
    total_gowns INT DEFAULT 0,
    total_revenue DECIMAL(10,2) DEFAULT 0.00,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for Admin Activity Log
CREATE TABLE IF NOT EXISTS admin_activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT,
    activity_type VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id)
);

-- Insert default admin user
INSERT INTO users (full_name, email, password, role) VALUES
('Admin', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- Initialize admin dashboard stats
INSERT INTO admin_dashboard_stats (total_users, total_appointments, total_gowns, total_revenue) 
VALUES (1, 0, 0, 0.00);

-- Add indexes for better query performance
CREATE INDEX idx_user_id ON bookings(user_id);
CREATE INDEX idx_gown_id ON bookings(gown_id);
CREATE INDEX idx_booking_date ON bookings(booking_date);
CREATE INDEX idx_status ON bookings(status);
CREATE INDEX idx_payment_status ON bookings(payment_status);
