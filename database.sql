-- ============================================
-- FLAVORIO - MySQL Database
-- Import this file using phpMyAdmin or run:
-- mysql -u root -p < database.sql
-- ============================================

-- Create and select the database
CREATE DATABASE IF NOT EXISTS recipe_book;
USE recipe_book;

-- ============================================
-- TABLE: users
-- Stores all registered user accounts
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,          -- Stored as bcrypt hash
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE: recipes
-- Stores all recipes submitted by users
-- ============================================
CREATE TABLE recipes (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    ingredients TEXT NOT NULL,
    instructions TEXT NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- TABLE: messages
-- Stores contact form submissions
-- ============================================
CREATE TABLE IF NOT EXISTS messages (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    message    TEXT         NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- SAMPLE DATA (optional — for testing)
-- ============================================

-- Sample user (password is: password123)
INSERT INTO users (username, email, password) VALUES
('Admin', 'admin@flavorio.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Sample recipes
INSERT INTO recipes (user_id, title, description, category, difficulty, prep_time, cook_time, servings, ingredients, instructions) VALUES
(1, 'Sri Lankan Pol Sambol', 
 'A classic coconut relish with chili and onion — perfect with rice or bread.',
 'Sri Lankan', 'Easy', '10 min', '0 min', 4,
 '1 cup coconut, grated\n2 green chilies\n1 small onion, sliced\nSalt to taste\n1 lime, juiced',
 'Step 1: Mix grated coconut with sliced onion and green chilies.\nStep 2: Add salt and lime juice.\nStep 3: Mix well and serve fresh.'),

(1, 'Chicken Curry',
 'Aromatic spiced chicken curry slow-cooked to perfection with coconut milk.',
 'Curry', 'Medium', '20 min', '40 min', 6,
 '500g chicken pieces\n1 cup coconut milk\n2 tbsp curry powder\n1 onion, diced\n3 garlic cloves\nSalt to taste',
 'Step 1: Fry onion and garlic until golden.\nStep 2: Add chicken and curry powder, mix well.\nStep 3: Add coconut milk and simmer for 40 minutes.');
