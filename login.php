<?php

session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (is_logged_in()) {
    redirect('../dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = clean_input($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";

    } else {
        $email_db = mysqli_real_escape_string($conn, $email);
        $result   = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email_db'");

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email']    = $user['email'];
                redirect('../dashboard.php');
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "No account found with that email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FLAVORIO - Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/>
  <link rel="stylesheet" href="../css/style.css"/>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="../index.php">
      <i class="bi bi-journal-richtext text-warning me-1"></i> FLAVORIO
    </a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
        <li class="nav-item"><a class="btn btn-outline-dark btn-sm px-3" href="register.php">Register</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container login-wrapper">
  <div class="card login-card">

    <div class="login-icon-circle">
      <i class="bi bi-box-arrow-in-right"></i>
    </div>

    <h4 class="text-center fw-bold mb-1">Welcome Back</h4>
    <p class="text-center text-muted mb-4 small">Log in to access your personal recipe collection.</p>

    <?php if ($error) echo show_error($error); ?>

    <form method="POST" action="login.php" id="loginForm" novalidate>

      <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
          <input type="email" name="email" id="emailInput" class="form-control"
                 placeholder="you@gmail.com" required
                 value="<?php echo isset($_POST['email']) ? clean_input($_POST['email']) : ''; ?>"/>
        </div>
        <div class="form-text text-danger d-none" id="emailError">Please enter a valid email.</div>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold">Password</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
          <input type="password" name="password" id="passwordInput" class="form-control"
                 placeholder="••••••••" required/>
          <button class="btn btn-outline-secondary" type="button" id="togglePwd" tabindex="-1">
            <i class="bi bi-eye" id="eyeIcon"></i>
          </button>
        </div>
        <div class="form-text text-danger d-none" id="pwdError">Password cannot be empty.</div>
      </div>

      <button type="submit" class="btn btn-dark w-100 py-2" id="loginBtn">
        <i class="bi bi-box-arrow-in-right me-1"></i> Login
      </button>

    </form>

    <p class="text-center mt-3 mb-0 small">
      Don't have an account? <a href="register.php" class="text-dark fw-semibold">Sign up</a>
    </p>

  </div>
</div>

<footer>
  <div class="container text-center">
    <p class="mb-1"><i class="bi bi-journal-richtext text-warning me-1"></i> <strong class="text-white">FLAVORIO</strong></p>
    <p class="mb-0 small">© 2026 FLAVORIO. Digital Recipe Book</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

document.getElementById('togglePwd').addEventListener('click', function () {
  const input = document.getElementById('passwordInput');
  const icon  = document.getElementById('eyeIcon');
  if (input.type === 'password') {
    input.type = 'text';
    icon.className = 'bi bi-eye-slash';
  } else {
    input.type = 'password';
    icon.className = 'bi bi-eye';
  }
});

document.getElementById('loginForm').addEventListener('submit', function (e) {
  let valid = true;

  const email = document.getElementById('emailInput');
  const pwd   = document.getElementById('passwordInput');
  const emailErr = document.getElementById('emailError');
  const pwdErr   = document.getElementById('pwdError');


  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email.value.trim())) {
    emailErr.classList.remove('d-none');
    email.classList.add('is-invalid');
    valid = false;
  } else {
    emailErr.classList.add('d-none');
    email.classList.remove('is-invalid');
  }


  if (pwd.value.trim() === '') {
    pwdErr.classList.remove('d-none');
    pwd.classList.add('is-invalid');
    valid = false;
  } else {
    pwdErr.classList.add('d-none');
    pwd.classList.remove('is-invalid');
  }

  if (!valid) e.preventDefault();


  if (valid) {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Logging in...';
    btn.disabled = true;
  }
});
</script>
</body>
</html>
