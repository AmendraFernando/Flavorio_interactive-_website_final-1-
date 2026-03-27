# 🍽️ FLAVORIO — Digital Recipe Book

A PHP + MySQL web application for sharing and managing recipes.
Built for Rajarata University of Sri Lanka — Web Development Phase 3.

---

## 📁 Folder Structure

```
flavorio/
│── css/
│   └── style.css              ← Your existing stylesheet
│── images/
│   └── recipes/               ← Uploaded recipe images (auto-created)
│── includes/
│   ├── db.php                 ← Database connection
│   └── functions.php          ← Helper functions
│── auth/
│   ├── register.php           ← User registration
│   ├── login.php              ← User login
│   └── logout.php             ← Logout (destroys session)
│── index.php                  ← Home page (recipe listing)
│── add-recipe.php             ← Add a new recipe (login required)
│── my-recipes.php             ← Manage your recipes (login required)
│── dashboard.php              ← User dashboard (login required)
│── contact.php                ← Contact form
│── database.sql               ← MySQL database dump
└── README.md                  ← This file
```

---

## ⚙️ Setup Instructions

### Step 1 — Install XAMPP
Download from https://www.apachefriends.org and install it.

### Step 2 — Copy Project Files
Copy the entire `flavorio` folder to:
```
C:\xampp\htdocs\flavorio\
```

### Step 3 — Start XAMPP
Open XAMPP Control Panel and start:
- ✅ Apache
- ✅ MySQL

### Step 4 — Import the Database
1. Open your browser and go to: http://localhost/phpmyadmin
2. Click **"New"** on the left sidebar
3. Type `recipe_book` as the database name → click **Create**
4. Click the **Import** tab
5. Click **Choose File** → select `database.sql`
6. Click **Go** to import

### Step 5 — Check Database Connection
Open `includes/db.php` and confirm these match your XAMPP setup:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');   // default XAMPP username
define('DB_PASS', '');       // default XAMPP password is empty
define('DB_NAME', 'recipe_book');
```

### Step 6 — Run the Project
Open your browser and go to:
```
http://localhost/flavorio/
```

---

## 🔑 Test Login (Sample Account)

After importing the database, you can log in with:
- **Email:** admin@flavorio.com
- **Password:** password123

---

## 📄 Pages Overview

| Page | URL | Description |
|------|-----|-------------|
| Home | `/index.php` | View all recipes |
| Register | `/auth/register.php` | Create an account |
| Login | `/auth/login.php` | Log into your account |
| Logout | `/auth/logout.php` | End your session |
| Add Recipe | `/add-recipe.php` | Submit a new recipe |
| My Recipes | `/my-recipes.php` | Manage your recipes |
| Dashboard | `/dashboard.php` | View your stats |
| Contact | `/contact.php` | Send a message |

---

## 🛡️ Security Features

- Passwords hashed using `password_hash()` (bcrypt)
- All inputs sanitized with `mysqli_real_escape_string()`
- Session-based authentication
- File upload validation (type + size check)

---

## 🗄️ Database Tables

| Table | Purpose |
|-------|---------|
| `users` | Registered user accounts |
| `recipes` | All submitted recipes |
| `messages` | Contact form submissions |
