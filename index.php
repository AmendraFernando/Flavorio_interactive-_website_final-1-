<?php
// ===== HOME PAGE =====
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Fetch all recipes from database
$recipes = mysqli_query($conn, "SELECT r.*, u.username FROM recipes r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FLAVORIO - Home</title>
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
        <li class="nav-item"><a class="nav-link active fw-semibold" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="add-recipe.php">+ Add Recipe</a></li>
        <li class="nav-item"><a class="nav-link" href="my-recipes.php">My Recipes</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

        <?php if (is_logged_in()): ?>
          <!-- Logged in: show username + logout -->
          <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-person-circle me-1"></i><?php echo $_SESSION['username']; ?></a></li>
          <li class="nav-item"><a class="btn btn-outline-dark btn-sm px-3" href="auth/logout.php">Logout</a></li>
        <?php else: ?>
          <!-- Not logged in: show login button -->
          <li class="nav-item"><a class="btn btn-dark btn-sm px-3" href="auth/login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container">

  <!-- Hero -->
  <div class="hero-banner">
    <h4>Welcome to our recipe book.</h4>
    <p class="mb-0 text-muted">Discover delicious recipes, save your favorites, and add your own culinary creations.</p>
  </div>

  <!-- Search & Filter -->
  <div class="row mb-4 g-2">
    <div class="col-md-8">
      <div class="input-group">
        <span class="input-group-text bg-white border-end-0">
          <i class="bi bi-search text-muted"></i>
        </span>
        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search recipes..."/>
      </div>
    </div>
    <div class="col-md-4">
      <select class="form-select" id="difficultyFilter">
        <option value="">All Levels</option>
        <option>Easy</option>
        <option>Medium</option>
        <option>Hard</option>
      </select>
    </div>
  </div>

  <!-- Recipe Cards from Database -->
  <div class="row g-4" id="recipeGrid">

    <?php if (mysqli_num_rows($recipes) > 0): ?>
      <?php while ($recipe = mysqli_fetch_assoc($recipes)): ?>
        <div class="col-md-4 recipe-item"
             data-title="<?php echo strtolower($recipe['title']); ?>"
             data-difficulty="<?php echo $recipe['difficulty']; ?>">
          <div class="card recipe-card">

            <?php if (!empty($recipe['image_path'])): ?>
              <img src="<?php echo $recipe['image_path']; ?>" class="card-img-top"
                   style="height:200px; object-fit:cover; border-radius:12px 12px 0 0;" alt="<?php echo $recipe['title']; ?>"/>
            <?php else: ?>
              <div class="card-img-placeholder">
                <i class="bi bi-image"></i>
              </div>
            <?php endif; ?>

            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-muted"><?php echo $recipe['category']; ?></small>
                <?php
                  $badge = 'bg-success';
                  if ($recipe['difficulty'] === 'Medium') $badge = 'bg-warning text-dark';
                  if ($recipe['difficulty'] === 'Hard')   $badge = 'bg-danger';
                ?>
                <span class="badge <?php echo $badge; ?>"><?php echo $recipe['difficulty']; ?></span>
              </div>
              <h6 class="card-title fw-bold"><?php echo $recipe['title']; ?></h6>
              <p class="card-text text-muted small"><?php echo substr($recipe['description'], 0, 90) . '...'; ?></p>
              <small class="text-muted"><i class="bi bi-person me-1"></i><?php echo $recipe['username']; ?></small>
            </div>

            <div class="card-footer bg-white border-0 meta-row d-flex gap-3 pb-3">
              <span><i class="bi bi-clock"></i> <?php echo $recipe['prep_time']; ?></span>
              <span><i class="bi bi-fire"></i> <?php echo $recipe['cook_time']; ?></span>
              <span><i class="bi bi-people"></i> <?php echo $recipe['servings']; ?></span>
            </div>

          </div>
        </div>
      <?php endwhile; ?>

    <?php else: ?>
      <!-- No recipes yet — show static sample cards -->
      <div class="col-12 text-center text-muted py-5">
        <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
        <h5>No recipes added yet.</h5>
        <a href="add-recipe.php" class="btn btn-dark mt-2">Add the first recipe</a>
      </div>
    <?php endif; ?>

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
<script>
// Live search and filter
function filterRecipes() {
  const search     = document.getElementById('searchInput').value.toLowerCase();
  const difficulty = document.getElementById('difficultyFilter').value;
  const items      = document.querySelectorAll('.recipe-item');

  items.forEach(item => {
    const title      = item.getAttribute('data-title');
    const itemDiff   = item.getAttribute('data-difficulty');
    const matchText  = title.includes(search);
    const matchDiff  = difficulty === '' || itemDiff === difficulty;

    item.style.display = (matchText && matchDiff) ? '' : 'none';
  });
}

document.getElementById('searchInput').addEventListener('input', filterRecipes);
document.getElementById('difficultyFilter').addEventListener('change', filterRecipes);
</script>
</body>
</html>
