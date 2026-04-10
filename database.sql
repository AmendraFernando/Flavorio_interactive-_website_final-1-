-- ============================================
-- FLAVORIO - MySQL Database
-- Import using phpMyAdmin OR run:
-- mysql -u root -p < database.sql
-- ============================================

CREATE DATABASE IF NOT EXISTS flavorio_db;
USE flavorio_db;

-- ============================================
-- TABLE: users
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE: recipes
-- ============================================
CREATE TABLE IF NOT EXISTS recipes (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT NOT NULL,
    title        VARCHAR(255) NOT NULL,
    description  TEXT NOT NULL,
    category     VARCHAR(100) DEFAULT '',
    difficulty   VARCHAR(20)  DEFAULT '',
    prep_time    VARCHAR(50)  DEFAULT '',
    cook_time    VARCHAR(50)  DEFAULT '',
    servings     INT DEFAULT 1,
    ingredients  TEXT NOT NULL,
    instructions TEXT NOT NULL,
    image_path   VARCHAR(255) DEFAULT '',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- TABLE: messages
-- ============================================
CREATE TABLE IF NOT EXISTS messages (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    message    TEXT         NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- SAMPLE DATA (for testing)
-- ============================================

-- Sample user  (password: password123)
INSERT INTO users (username, email, password) VALUES
('Admin', 'admin@flavorio.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Sample recipes
INSERT INTO recipes (user_id, title, description, category, difficulty, prep_time, cook_time, servings, ingredients, instructions) VALUES
(1, 'Sri Lankan Pol Sambol',
 'A classic coconut relish with chili and onion — perfect with rice or bread.',
 'Sri Lankan', 'Easy', '10 min', '0 min', 4,
 '1 cup coconut, grated
2 green chilies
1 small onion, sliced
Salt to taste
1 lime, juiced',
 'Step 1: Mix grated coconut with sliced onion and green chilies.
Step 2: Add salt and lime juice.
Step 3: Mix well and serve fresh.'),

(1, 'Chicken Curry',
 'Aromatic spiced chicken curry slow-cooked to perfection with coconut milk.',
 'Curry', 'Medium', '20 min', '40 min', 6,
 '500g chicken pieces
1 cup coconut milk
2 tbsp curry powder
1 onion, diced
3 garlic cloves
Salt to taste',
 'Step 1: Fry onion and garlic until golden.
Step 2: Add chicken and curry powder, mix well.
Step 3: Add coconut milk and simmer for 40 minutes.'),

(1, 'Chocolate Lava Cake',
 'Warm, gooey chocolate lava cakes with a molten centre — a crowd favourite.',
 'Dessert', 'Hard', '15 min', '12 min', 4,
 '100g dark chocolate
100g butter
2 eggs
2 egg yolks
60g sugar
30g flour
Pinch of salt',
 'Step 1: Melt chocolate and butter together.
Step 2: Whisk eggs, yolks, and sugar until thick.
Step 3: Fold chocolate mix and flour into eggs.
Step 4: Pour into greased ramekins and bake at 200°C for 12 minutes.');
