<?php
// ===== CONTACT PAGE =====
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$error   = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name    = clean_input($_POST['name']);
    $email   = clean_input($_POST['email']);
    $message = clean_input($_POST['message']);

    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        $error = "All fields are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";

    } elseif (strlen($message) < 10) {
        $error = "Message must be at least 10 characters long.";

    } else {
        // Insert into messages table
        $name_db    = mysqli_real_escape_string($conn, $name);
        $email_db   = mysqli_real_escape_string($conn, $email);
        $message_db = mysqli_real_escape_string($conn, $message);

        $sql = "INSERT INTO messages (name, email, message) VALUES ('$name_db', '$email_db', '$message_db')";

        if (mysqli_query($conn, $sql)) {
            $success = "Thank you, $name! Your message has been sent successfully.";
            // Clear fields after success
            $_POST = [];
        } else {
            $error = "Failed to send message. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FLAVORIO - Contact</title>
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
        <li class="nav-item"><a class="nav-link active fw-semibold" href="contact.php">Contact</a></li>
        <?php if (is_logged_in()): ?>
          <li class="nav-item"><a class="btn btn-outline-dark btn-sm px-3" href="auth/logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="btn btn-dark btn-sm px-3" href="auth/login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">

      <div class="card form-card">

        <div class="login-icon-circle mb-3">
          <i class="bi bi-envelope"></i>
        </div>

        <h4 class="fw-bold text-center mb-1">Contact Us</h4>
        <p class="text-muted text-center mb-4 small">Have a question or suggestion? We'd love to hear from you.</p>

        <!-- Messages -->
        <?php if ($error)   echo show_error($error); ?>
        <?php if ($success) echo show_success($success); ?>

        <form method="POST" action="contact.php">

          <div class="mb-3">
            <label class="form-label fw-semibold">Your Name <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
              <input type="text" name="name" class="form-control" placeholder="John Silva" required
                     value="<?php echo isset($_POST['name']) ? clean_input($_POST['name']) : ''; ?>"/>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
              <input type="email" name="email" class="form-control" placeholder="you@gmail.com" required
                     value="<?php echo isset($_POST['email']) ? clean_input($_POST['email']) : ''; ?>"/>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
            <textarea name="message" class="form-control" rows="5" placeholder="Write your message here..." required><?php echo isset($_POST['message']) ? clean_input($_POST['message']) : ''; ?></textarea>
          </div>

          <button type="submit" class="btn btn-dark w-100 py-2">
            <i class="bi bi-send me-1"></i> Send Message
          </button>

        </form>
      </div>

    </div>
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
