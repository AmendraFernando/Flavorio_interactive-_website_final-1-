<?php
// ===== MY RECIPES PAGE =====
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Must be logged in
if (!is_logged_in()) {
    redirect('auth/login.php');
}

$user_id = $_SESSION['user_id'];

// Handle recipe deletion
if (isset($_GET['delete'])) {
    $recipe_id = (int)$_GET['delete'];
    // Only delete if recipe belongs to this user
    mysqli_query($conn, "DELETE FROM recipes WHERE id = $recipe_id AND user_id = $user_id");
    redirect('my-recipes.php');
}

// Get this user's recipes
$my_recipes = mysqli_query($conn, "SELECT * FROM recipes WHERE user_id = $user_id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FLAVORIO - My Recipes</title>
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
        <li class="nav-item"><a class="nav-link" href="add-recipe.php">+ Add Recipe</a></li>
        <li class="nav-item"><a class="nav-link active fw-semibold" href="my-recipes.php">My Recipes</a></li>
        <li class="nav-item"><a class="btn btn-outline-dark btn-sm px-3" href="auth/logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-4">

  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-start mb-4">
    <div>
      <h4 class="fw-bold mb-1">My Recipes</h4>
      <p class="text-muted mb-0">Manage your submitted recipes.</p>
    </div>
    <a href="add-recipe.php" class="btn btn-dark rounded-pill px-4">
      <i class="bi bi-plus"></i> Add Recipe
    </a>
  </div>

  <?php if (mysqli_num_rows($my_recipes) > 0): ?>

    <div class="row g-4">
      <?php while ($recipe = mysqli_fetch_assoc($my_recipes)): ?>
        <div class="col-md-4">
          <div class="card recipe-card">

            <?php if (!empty($recipe['image_path'])): ?>
              <img src="<?php echo $recipe['image_path']; ?>" class="card-img-top"
                   style="height:180px; object-fit:cover; border-radius:12px 12px 0 0;" alt="<?php echo $recipe['title']; ?>"/>
            <?php else: ?>
              <div class="card-img-placeholder"><i class="bi bi-image"></i></div>
            <?php endif; ?>

            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted"><?php echo $recipe['category']; ?></small>
                <?php
                  $badge = 'bg-success';
                  if ($recipe['difficulty'] === 'Medium') $badge = 'bg-warning text-dark';
                  if ($recipe['difficulty'] === 'Hard')   $badge = 'bg-danger';
                ?>
                <span class="badge <?php echo $badge; ?>"><?php echo $recipe['difficulty']; ?></span>
              </div>
              <h6 class="card-title fw-bold"><?php echo $recipe['title']; ?></h6>
              <p class="card-text text-muted small"><?php echo substr($recipe['description'], 0, 80) . '...'; ?></p>
            </div>

            <div class="card-footer bg-white border-0 d-flex gap-2 pb-3">
              <!-- Delete button with confirmation -->
              <a href="my-recipes.php?delete=<?php echo $recipe['id']; ?>"
                 class="btn btn-sm btn-outline-danger"
                 onclick="return confirm('Are you sure you want to delete this recipe?');">
                <i class="bi bi-trash"></i> Delete
              </a>
            </div>

          </div>
        </div>
      <?php endwhile; ?>
    </div>

  <?php else: ?>
    <!-- Empty state -->
    <div class="card empty-card">
      <div class="plus-circle"><i class="bi bi-plus"></i></div>
      <h5 class="fw-bold mb-2">No Recipes Yet</h5>
      <p class="text-muted mb-4">Start by adding your first recipe to your collection.</p>
      <a href="add-recipe.php" class="btn btn-dark rounded-pill px-4">
        <i class="bi bi-plus"></i> Add your First Recipe
      </a>
    </div>
  <?php endif; ?>

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
