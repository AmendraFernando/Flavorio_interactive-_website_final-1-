# 🍽️ FLAVORIO — Digital Recipe Book

A PHP + MySQL web application for sharing and managing recipes.
Built for **Rajarata University of Sri Lanka** — Web Development Phase 3.

---

## 📌 Project Info

| | |
|---|---|
| **Project Name** | FLAVORIO |
| **Type** | Web Application |
| **Language** | PHP, MySQL, HTML, CSS, JavaScript |
| **Framework** | Bootstrap 5.3 |
| **Server** | WAMP (Apache + MariaDB) |
| **University** | Rajarata University of Sri Lanka |
| **Module** | ICT 2204 — Web Development Phase 3 |

---

## 👨‍💻 Developers

| Name | Index No | Reg No |
|---|---|---|
| K.R.A. Fernando | 6013 | ICT/2023/020 |
| H.P. D.N.D. Ariyathilaka | 6034 | ICT/2023/044 |

---

## 📁 Folder Structure

```
flavorio/
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── css/
│   └── style.css
├── includes/
│   ├── db.php
│   └── functions.php
├── images/
│   └── recipes/
├── index.php
├── add-recipe.php
├── my-recipes.php
├── dashboard.php
├── contact.php
└── database.sql
```

---

## ✨ Features

- User Registration and Login with password hashing
- Add, View and Delete Recipes
- Recipe Image Upload
- Live Search and Difficulty Filter
- User Dashboard with recipe count
- Contact Form
- Fully responsive with Bootstrap 5

---

## ⚡ JavaScript Features

| Page | Features |
|---|---|
| Home | Page loader, live search, difficulty filter, back-to-top button |
| Login | Show/hide password, client-side validation, loading spinner |
| Register | Password strength bar, live password match, validation, loading spinner |
| Add Recipe | Character counters, image preview, drag-drop highlight, loading spinner |
| My Recipes | Delete confirmation modal |
| Dashboard | Animated number counter, staggered card animations |
| Contact | Character counter, validation, loading spinner |

---

## 🚀 How to Run Locally (WAMP)

**Step 1 — Copy project folder**
```
Copy the flavorio/ folder into C:/wamp64/www/
```

**Step 2 — Start WAMP**
```
Make sure the WAMP icon is green in the taskbar
```

**Step 3 — Create the database**
```
Open http://localhost/phpmyadmin
Create a new database named: flavorio_db
Import the database.sql file
```

**Step 4 — Open the website**
```
http://localhost/flavorio/
```

---

## 🔐 Default Login

| Email | Password |
|---|---|
| admin@flavorio.com | password123 |

---

## 🗄️ Database Tables

| Table | Description |
|---|---|
| `users` | Stores registered user accounts |
| `recipes` | Stores all submitted recipes |
| `messages` | Stores contact form submissions |

---

## 🛠️ Built With

- PHP 8.3
- MySQL / MariaDB
- Bootstrap 5.3
- Bootstrap Icons
- JavaScript (Vanilla)
- WAMP Server

---

© 2026 FLAVORIO — Rajarata University of Sri Lanka
