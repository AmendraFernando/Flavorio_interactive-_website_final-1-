<?php

session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name    = clean_input($_POST['name']);
    $email   = clean_input($_POST['email']);
    $message = clean_input($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        $error = "All fields are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";

    } elseif (strlen($message) < 10) {
        $error = "Message must be at least 10 characters long.";

    } else {
        $name_db    = mysqli_real_escape_string($conn, $name);
        $email_db   = mysqli_real_escape_string($conn, $email);
        $message_db = mysqli_real_escape_string($conn, $message);

        $sql = "INSERT INTO messages (name, email, message) VALUES ('$name_db', '$email_db', '$message_db')";

        if (mysqli_query($conn, $sql)) {
            $success = "Thank you, " . htmlspecialchars($name) . "! Your message has been sent successfully.";
            $_POST   = [];
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

        <?php if ($error)   echo show_error($error); ?>
        <?php if ($success) echo show_success($success); ?>

        <form method="POST" action="contact.php" id="contactForm" novalidate>

          <div class="mb-3">
            <label class="form-label fw-semibold">Your Name <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
              <input type="text" name="name" id="contactName" class="form-control"
                     placeholder="John Silva" required
                     value="<?php echo isset($_POST['name']) ? clean_input($_POST['name']) : ''; ?>"/>
            </div>
            <div class="form-text text-danger d-none" id="nameError">Name is required.</div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
              <input type="email" name="email" id="contactEmail" class="form-control"
                     placeholder="you@gmail.com" required
                     value="<?php echo isset($_POST['email']) ? clean_input($_POST['email']) : ''; ?>"/>
            </div>
            <div class="form-text text-danger d-none" id="contactEmailError">Enter a valid email.</div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
            <textarea name="message" id="contactMessage" class="form-control" rows="5"
                      placeholder="Write your message here..." required
                      maxlength="1000"><?php echo isset($_POST['message']) ? clean_input($_POST['message']) : ''; ?></textarea>
            <div class="d-flex justify-content-between">
              <div class="form-text text-danger d-none" id="msgError">Message must be at least 10 characters.</div>
              <small class="char-counter ms-auto" id="msgCount">0/1000</small>
            </div>
          </div>

          <button type="submit" class="btn btn-dark w-100 py-2" id="sendBtn">
            <i class="bi bi-send me-1"></i> Send Message
          </button>

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

const msgArea  = document.getElementById('contactMessage');
const msgCount = document.getElementById('msgCount');
msgArea.addEventListener('input', function () {
  const len = this.value.length;
  msgCount.textContent = len + '/1000';
  msgCount.classList.toggle('over', len >= 1000);
});

document.getElementById('contactForm').addEventListener('submit', function (e) {
  let valid = true;

  const name    = document.getElementById('contactName');
  const email   = document.getElementById('contactEmail');
  const message = document.getElementById('contactMessage');


  if (name.value.trim() === '') {
    document.getElementById('nameError').classList.remove('d-none');
    name.classList.add('is-invalid');
    valid = false;
  } else {
    document.getElementById('nameError').classList.add('d-none');
    name.classList.remove('is-invalid');
  }


  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email.value.trim())) {
    document.getElementById('contactEmailError').classList.remove('d-none');
    email.classList.add('is-invalid');
    valid = false;
  } else {
    document.getElementById('contactEmailError').classList.add('d-none');
    email.classList.remove('is-invalid');
  }


  if (message.value.trim().length < 10) {
    document.getElementById('msgError').classList.remove('d-none');
    message.classList.add('is-invalid');
    valid = false;
  } else {
    document.getElementById('msgError').classList.add('d-none');
    message.classList.remove('is-invalid');
  }

  if (!valid) e.preventDefault();

  if (valid) {
    const btn = document.getElementById('sendBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
    btn.disabled = true;
  }
});
</script>
</body>
</html>
