<?php
// ===== ADD RECIPE PAGE =====
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Must be logged in to add a recipe
if (!is_logged_in()) {
    redirect('auth/login.php');
}

$error   = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title        = clean_input($_POST['title']);
    $description  = clean_input($_POST['description']);
    $category     = clean_input($_POST['category']);
    $difficulty   = clean_input($_POST['difficulty']);
    $prep_time    = clean_input($_POST['prep_time']);
    $cook_time    = clean_input($_POST['cook_time']);
    $servings     = (int)$_POST['servings'];
    $ingredients  = clean_input($_POST['ingredients']);
    $instructions = clean_input($_POST['instructions']);
    $user_id      = $_SESSION['user_id'];

    // Validation
    if (empty($title) || empty($description) || empty($ingredients) || empty($instructions)) {
        $error = "Please fill in all required fields.";

    } else {
        // Handle image upload
        $image_path = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext     = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $error = "Only JPG, PNG, GIF, and WEBP images are allowed.";
            } elseif ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
                $error = "Image must be less than 5MB.";
            } else {
                // Save image to images folder
                $new_filename = 'recipe_' . time() . '_' . uniqid() . '.' . $ext;
                $upload_dir   = 'images/recipes/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $new_filename);
                $image_path = $upload_dir . $new_filename;
            }
        }

        if (empty($error)) {
            // Escape all values before inserting
            $title_db        = mysqli_real_escape_string($conn, $title);
            $description_db  = mysqli_real_escape_string($conn, $description);
            $category_db     = mysqli_real_escape_string($conn, $category);
            $difficulty_db   = mysqli_real_escape_string($conn, $difficulty);
            $prep_time_db    = mysqli_real_escape_string($conn, $prep_time);
            $cook_time_db    = mysqli_real_escape_string($conn, $cook_time);
            $ingredients_db  = mysqli_real_escape_string($conn, $ingredients);
            $instructions_db = mysqli_real_escape_string($conn, $instructions);
            $image_path_db   = mysqli_real_escape_string($conn, $image_path);

            $sql = "INSERT INTO recipes 
                    (title, description, category, difficulty, prep_time, cook_time, servings, ingredients, instructions, image_path, user_id)
                    VALUES 
                    ('$title_db', '$description_db', '$category_db', '$difficulty_db', '$prep_time_db', '$cook_time_db',
                     $servings, '$ingredients_db', '$instructions_db', '$image_path_db', $user_id)";

            if (mysqli_query($conn, $sql)) {
                $success = "Recipe added successfully! <a href='index.php' class='alert-link'>View all recipes</a>.";
            } else {
                $error = "Failed to save recipe. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FLAVORIO - Add New Recipe</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/>
  <link rel="stylesheet" href="css/style.css"/>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <i class="bi bi-journal-richtext text-warning me-1"></i> FLAVORIO
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-2">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link active fw-semibold" href="add-recipe.php">+ Add Recipe</a></li>
        <li class="nav-item"><a class="nav-link" href="my-recipes.php">My Recipes</a></li>
        <li class="nav-item">
          <a class="btn btn-outline-dark btn-sm px-3" href="auth/logout.php">
            Logout (<?php echo $_SESSION['username']; ?>)
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-4">

  <a href="index.php" class="text-dark text-decoration-none d-inline-block mb-3">
    <i class="bi bi-chevron-left"></i> Back to Recipes
  </a>

  <div class="card form-card">
    <h4 class="fw-bold mb-1">Add New Recipe</h4>
    <p class="text-muted mb-4">Share your favorite recipe with the community.</p>

    <!-- Messages -->
    <?php if ($error)   echo show_error($error); ?>
    <?php if ($success) echo show_success($success); ?>

    <form method="POST" action="add-recipe.php" enctype="multipart/form-data">

      <!-- Title -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Recipe Title <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control" placeholder="Eg:- Sri Lankan Pol Sambol" required
               value="<?php echo isset($_POST['title']) ? clean_input($_POST['title']) : ''; ?>"/>
      </div>

      <!-- Description -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
        <textarea name="description" class="form-control" rows="3" placeholder="Briefly describe your recipe..." required><?php echo isset($_POST['description']) ? clean_input($_POST['description']) : ''; ?></textarea>
      </div>

      <!-- Category & Difficulty -->
      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Category</label>
          <input type="text" name="category" class="form-control" placeholder="Eg:- Dessert, Italian, etc."
                 value="<?php echo isset($_POST['category']) ? clean_input($_POST['category']) : ''; ?>"/>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Difficulty</label>
          <select name="difficulty" class="form-select">
            <option value="">Select difficulty</option>
            <option <?php echo (isset($_POST['difficulty']) && $_POST['difficulty']==='Easy')   ? 'selected' : ''; ?>>Easy</option>
            <option <?php echo (isset($_POST['difficulty']) && $_POST['difficulty']==='Medium') ? 'selected' : ''; ?>>Medium</option>
            <option <?php echo (isset($_POST['difficulty']) && $_POST['difficulty']==='Hard')   ? 'selected' : ''; ?>>Hard</option>
          </select>
        </div>
      </div>

      <!-- Times & Servings -->
      <div class="row g-3 mb-3">
        <div class="col-4">
          <label class="form-label fw-semibold">Prep Time</label>
          <input type="text" name="prep_time" class="form-control" placeholder="e.g. 15 min"
                 value="<?php echo isset($_POST['prep_time']) ? clean_input($_POST['prep_time']) : ''; ?>"/>
        </div>
        <div class="col-4">
          <label class="form-label fw-semibold">Cook Time</label>
          <input type="text" name="cook_time" class="form-control" placeholder="e.g. 30 min"
                 value="<?php echo isset($_POST['cook_time']) ? clean_input($_POST['cook_time']) : ''; ?>"/>
        </div>
        <div class="col-4">
          <label class="form-label fw-semibold">Servings</label>
          <input type="number" name="servings" class="form-control" placeholder="e.g. 4" min="1"
                 value="<?php echo isset($_POST['servings']) ? (int)$_POST['servings'] : ''; ?>"/>
        </div>
      </div>

      <!-- Photo Upload -->
      <div class="mb-4">
        <label class="form-label fw-semibold">Recipe Photo</label>
        <div class="upload-zone">
          <i class="bi bi-image d-block mb-2"></i>
          <p class="text-muted mb-2">Click to upload a photo (max 5MB)</p>
          <input type="file" name="photo" class="form-control" accept="image/*"/>
        </div>
      </div>

      <!-- Ingredients -->
      <div class="mb-4">
        <label class="form-label fw-semibold">Ingredients <span class="text-danger">*</span></label>
        <textarea name="ingredients" class="form-control" rows="5"
                  placeholder="Enter each ingredient on a new line:&#10;1 cup coconut, grated&#10;2 green chilies&#10;Salt to taste" required><?php echo isset($_POST['ingredients']) ? clean_input($_POST['ingredients']) : ''; ?></textarea>
        <p class="text-muted small mt-1 mb-0">Enter each ingredient on a separate line.</p>
      </div>

      <!-- Instructions -->
      <div class="mb-4">
        <label class="form-label fw-semibold">Instructions <span class="text-danger">*</span></label>
        <textarea name="instructions" class="form-control" rows="6"
                  placeholder="Step 1: ...&#10;Step 2: ...&#10;Step 3: ..." required><?php echo isset($_POST['instructions']) ? clean_input($_POST['instructions']) : ''; ?></textarea>
        <p class="text-muted small mt-1 mb-0">Describe each step clearly.</p>
      </div>

      <!-- Buttons -->
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-dark px-4">
          <i class="bi bi-check-circle me-1"></i> Add Recipe
        </button>
        <a href="index.php" class="btn btn-secondary px-4">Cancel</a>
      </div>

    </form>
  </div>
</div>

<!-- Footer -->
<footer>
  <div class="container text-center">
    <p class="mb-1"><i class="bi bi-journal-richtext text-warning me-1"></i> <strong class="text-white">FLAVORIO</strong></p>
    <p class="mb-0 small">© 2026 FLAVORIO. Digital Recipe Book — Rajarata University of Sri Lanka</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
