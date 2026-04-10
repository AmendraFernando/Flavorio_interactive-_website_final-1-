<?php

session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!is_logged_in()) {
    redirect('auth/login.php');
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['delete_id'])) {
    $recipe_id = (int)$_POST['delete_id'];
    mysqli_query($conn, "DELETE FROM recipes WHERE id = $recipe_id AND user_id = $user_id");
    redirect('my-recipes.php');
}

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

    <div class="row g-4" id="myRecipeGrid">
      <?php while ($recipe = mysqli_fetch_assoc($my_recipes)): ?>
        <div class="col-md-4 recipe-item">
          <div class="card recipe-card">

            <?php if (!empty($recipe['image_path'])): ?>
              <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" class="card-img-top"
                   style="height:180px; object-fit:cover; border-radius:12px 12px 0 0;"
                   alt="<?php echo htmlspecialchars($recipe['title']); ?>"/>
            <?php else: ?>
              <div class="card-img-placeholder"><i class="bi bi-image"></i></div>
            <?php endif; ?>

            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted"><?php echo htmlspecialchars($recipe['category']); ?></small>
                <?php
                  $badge = 'bg-success';
                  if ($recipe['difficulty'] === 'Medium') $badge = 'bg-warning text-dark';
                  if ($recipe['difficulty'] === 'Hard')   $badge = 'bg-danger';
                ?>
                <span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($recipe['difficulty']); ?></span>
              </div>
              <h6 class="card-title fw-bold"><?php echo htmlspecialchars($recipe['title']); ?></h6>
              <p class="card-text text-muted small"><?php echo substr(htmlspecialchars($recipe['description']), 0, 80) . '...'; ?></p>
              <small class="text-muted"><i class="bi bi-calendar me-1"></i>
                <?php echo date('M d, Y', strtotime($recipe['created_at'])); ?>
              </small>
            </div>

            <div class="card-footer bg-white border-0 d-flex gap-2 pb-3">
              <!-- Delete triggers modal -->
              <button class="btn btn-sm btn-outline-danger"
                      onclick="confirmDelete(<?php echo $recipe['id']; ?>, '<?php echo addslashes(htmlspecialchars($recipe['title'])); ?>')">
                <i class="bi bi-trash"></i> Delete
              </button>
            </div>

          </div>
        </div>
      <?php endwhile; ?>
    </div>

  <?php else: ?>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-body text-center p-4">
        <i class="bi bi-exclamation-triangle-fill text-danger fs-1 mb-3 d-block"></i>
        <h5 class="fw-bold mb-1">Delete Recipe?</h5>
        <p class="text-muted mb-4" id="deleteModalMsg">This action cannot be undone.</p>
        <form method="POST" action="my-recipes.php">
          <input type="hidden" name="delete_id" id="deleteRecipeId"/>
          <div class="d-flex justify-content-center gap-3">
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger px-4">Yes, Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<footer>
  <div class="container text-center">
    <p class="mb-1"><i class="bi bi-journal-richtext text-warning me-1"></i> <strong class="text-white">FLAVORIO</strong></p>
    <p class="mb-0 small">© 2026 FLAVORIO. Digital Recipe Book — Rajarata University of Sri Lanka</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

function confirmDelete(id, title) {
  document.getElementById('deleteRecipeId').value = id;
  document.getElementById('deleteModalMsg').textContent = 'Are you sure you want to delete "' + title + '"? This cannot be undone.';
  new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
</body>
</html>
