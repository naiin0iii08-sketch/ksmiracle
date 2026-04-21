-- PostgreSQL Schema for Internship Management System

-- 1. Create Custom Types for Enums
CREATE TYPE user_role AS ENUM ('student', 'teacher', 'admin');
CREATE TYPE account_status AS ENUM ('active', 'inactive');

-- 2. Create Users Table
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role user_role NOT NULL,
    email VARCHAR(150),
    status account_status DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Create Students Table
CREATE TABLE IF NOT EXISTS students (
    student_id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    student_code VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    major VARCHAR(150),
    phone VARCHAR(20)
);

-- 4. Create Teachers Table
CREATE TABLE IF NOT EXISTS teachers (
    teacher_id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    staff_code VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    department VARCHAR(150),
    phone VARCHAR(20)
);

-- 5. Create Admins Table
CREATE TABLE IF NOT EXISTS admins (
    admin_id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    admin_code VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100)
);

-- 6. Create Login Logs Table
CREATE TABLE IF NOT EXISTS login_logs (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100),
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(50),
    status VARCHAR(50)
);

-- 7. Initial Seed Data (Admin password: admin123)
-- Delete existing if any for fresh start
DELETE FROM users WHERE username = 'admin';

INSERT INTO users (username, password, role, email, status) VALUES 
('admin', '$2y$10$d5ZNrngmTSqUlkCbdGuQN.ZhSBTMauHWMVnfEPht19uaI0JvdvnmW', 'admin', 'admin@swu.ac.th', 'active');

-- Link Admin profile
INSERT INTO admins (user_id, admin_code, first_name, last_name)
SELECT id, 'ADM001', 'Admin', 'System' FROM users WHERE username = 'admin';
