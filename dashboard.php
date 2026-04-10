<?php

session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!is_logged_in()) {
    redirect('auth/login.php');
}

$user_id = $_SESSION['user_id'];

$count_result  = mysqli_query($conn, "SELECT COUNT(*) as total FROM recipes WHERE user_id = $user_id");
$count_row     = mysqli_fetch_assoc($count_result);
$total_recipes = $count_row['total'];

$user_data = get_user($conn, $user_id);
$joined    = date('M Y', strtotime($user_data['created_at']));

$recent = mysqli_query($conn, "SELECT * FROM recipes WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FLAVORIO - Dashboard</title>
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
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="add-recipe.php">+ Add Recipe</a></li>
        <li class="nav-item"><a class="nav-link" href="my-recipes.php">My Recipes</a></li>
        <li class="nav-item"><a class="btn btn-outline-dark btn-sm px-3" href="auth/logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-4">

  <!-- Welcome Banner -->
  <div class="hero-banner mb-4">
    <h4>👋 Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>
    <p class="mb-0 text-muted">Here's a summary of your FLAVORIO activity.</p>
  </div>

  <!-- Stats Cards -->
  <div class="row g-3 mb-4" id="statsRow">
    <div class="col-md-4">
      <div class="card text-center p-4 border-0 shadow-sm rounded-4 stat-card">
        <i class="bi bi-journal-richtext fs-1 text-warning"></i>
        <h3 class="fw-bold mt-2 counter" data-target="<?php echo $total_recipes; ?>">0</h3>
        <p class="text-muted mb-0">Recipes Submitted</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center p-4 border-0 shadow-sm rounded-4 stat-card">
        <i class="bi bi-person-circle fs-1 text-dark"></i>
        <h5 class="fw-bold mt-2"><?php echo htmlspecialchars($_SESSION['username']); ?></h5>
        <p class="text-muted mb-0">Joined <?php echo $joined; ?></p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center p-4 border-0 shadow-sm rounded-4 stat-card">
        <i class="bi bi-plus-circle fs-1 text-success"></i>
        <a href="add-recipe.php" class="d-block btn btn-dark mt-3">Add New Recipe</a>
      </div>
    </div>
  </div>

  <!-- Recent Recipes -->
  <h5 class="fw-bold mb-3">Your Recent Recipes</h5>

  <?php if (mysqli_num_rows($recent) > 0): ?>
    <div class="row g-3">
      <?php while ($r = mysqli_fetch_assoc($recent)): ?>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm rounded-4 p-3 recipe-item">
            <div class="d-flex justify-content-between">
              <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($r['title']); ?></h6>
              <?php
                $badge = 'bg-success';
                if ($r['difficulty'] === 'Medium') $badge = 'bg-warning text-dark';
                if ($r['difficulty'] === 'Hard')   $badge = 'bg-danger';
              ?>
              <span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($r['difficulty']); ?></span>
            </div>
            <small class="text-muted"><?php echo htmlspecialchars($r['category']); ?> &bull;
              Added <?php echo date('M d, Y', strtotime($r['created_at'])); ?>
            </small>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p class="text-muted">You haven't added any recipes yet. <a href="add-recipe.php">Add your first one!</a></p>
  <?php endif; ?>

</div>

<footer>
  <div class="container text-center">
    <p class="mb-1"><i class="bi bi-journal-richtext text-warning me-1"></i> <strong class="text-white">FLAVORIO</strong></p>
    <p class="mb-0 small">© 2026 FLAVORIO. Digital Recipe Book — Rajarata University of Sri Lanka</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

document.querySelectorAll('.counter').forEach(el => {
  const target = parseInt(el.getAttribute('data-target')) || 0;
  if (target === 0) { el.textContent = '0'; return; }
  let current  = 0;
  const step   = Math.max(1, Math.ceil(target / 30));
  const timer  = setInterval(() => {
    current += step;
    if (current >= target) { el.textContent = target; clearInterval(timer); }
    else { el.textContent = current; }
  }, 40);
});

document.querySelectorAll('.stat-card').forEach((card, i) => {
  card.style.opacity  = '0';
  card.style.transform = 'translateY(20px)';
  card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
  setTimeout(() => {
    card.style.opacity  = '1';
    card.style.transform = 'translateY(0)';
  }, 100 + i * 100);
});
</script>
</body>
</html>
