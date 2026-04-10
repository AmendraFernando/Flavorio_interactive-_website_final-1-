<?php

session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (is_logged_in()) {
    redirect('../dashboard.php');
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = clean_input($_POST['username']);
    $email    = clean_input($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $error = "All fields are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";

    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";

    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";

    } else {
        $email_check = mysqli_real_escape_string($conn, $email);
        $check       = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email_check'");

        if (mysqli_num_rows($check) > 0) {
            $error = "An account with this email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $username_db     = mysqli_real_escape_string($conn, $username);
            $email_db        = mysqli_real_escape_string($conn, $email);

            $sql = "INSERT INTO users (username, email, password) VALUES ('$username_db', '$email_db', '$hashed_password')";

            if (mysqli_query($conn, $sql)) {
                $success = "Account created successfully! <a href='login.php' class='alert-link'>Click here to login</a>.";
            } else {
                $error = "Something went wrong. Please try again.";
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
  <title>FLAVORIO - Register</title>
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
        <li class="nav-item"><a class="btn btn-dark btn-sm px-3" href="login.php">Login</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container login-wrapper">
  <div class="card login-card" style="max-width:480px;">

    <div class="login-icon-circle">
      <i class="bi bi-person-plus"></i>
    </div>

    <h4 class="text-center fw-bold mb-1">Create Account</h4>
    <p class="text-center text-muted mb-4 small">Join FLAVORIO and start sharing your recipes.</p>

    <?php if ($error)   echo show_error($error); ?>
    <?php if ($success) echo show_success($success); ?>

    <form method="POST" action="register.php" id="registerForm" novalidate>

      <div class="mb-3">
        <label class="form-label fw-semibold">Username</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
          <input type="text" name="username" id="usernameInput" class="form-control"
                 placeholder="YourName" required
                 value="<?php echo isset($_POST['username']) ? clean_input($_POST['username']) : ''; ?>"/>
        </div>
        <div class="form-text text-danger d-none" id="usernameError">Username is required.</div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
          <input type="email" name="email" id="regEmail" class="form-control"
                 placeholder="you@gmail.com" required
                 value="<?php echo isset($_POST['email']) ? clean_input($_POST['email']) : ''; ?>"/>
        </div>
        <div class="form-text text-danger d-none" id="regEmailError">Enter a valid email.</div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Password</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
          <input type="password" name="password" id="regPassword" class="form-control"
                 placeholder="Min. 6 characters" required/>
          <button class="btn btn-outline-secondary" type="button" id="toggleRegPwd" tabindex="-1">
            <i class="bi bi-eye" id="regEyeIcon"></i>
          </button>
        </div>
        <!-- Strength bar -->
        <div class="mt-2">
          <div class="progress" style="height:5px;">
            <div id="strengthBar" class="progress-bar" role="progressbar" style="width:0%"></div>
          </div>
          <small id="strengthLabel" class="form-text"></small>
        </div>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold">Confirm Password</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-lock-fill"></i></span>
          <input type="password" name="confirm_password" id="confirmPassword"
                 class="form-control" placeholder="Repeat password" required/>
        </div>
        <div id="passwordMatchMsg" class="form-text"></div>
      </div>

      <button type="submit" class="btn btn-dark w-100 py-2" id="regBtn">
        <i class="bi bi-person-plus me-1"></i> Create Account
      </button>

    </form>

    <p class="text-center mt-3 mb-0 small">
      Already have an account? <a href="login.php" class="text-dark fw-semibold">Login</a>
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

document.getElementById('toggleRegPwd').addEventListener('click', function () {
  const input = document.getElementById('regPassword');
  const icon  = document.getElementById('regEyeIcon');
  input.type  = input.type === 'password' ? 'text' : 'password';
  icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
});

document.getElementById('regPassword').addEventListener('input', function () {
  const val = this.value;
  const bar = document.getElementById('strengthBar');
  const lbl = document.getElementById('strengthLabel');
  let strength = 0;

  if (val.length >= 6)  strength++;
  if (val.length >= 10) strength++;
  if (/[A-Z]/.test(val)) strength++;
  if (/[0-9]/.test(val)) strength++;
  if (/[^A-Za-z0-9]/.test(val)) strength++;

  const levels = [
    { pct: '0%',   cls: '',          label: '' },
    { pct: '20%',  cls: 'bg-danger', label: '😟 Very weak' },
    { pct: '40%',  cls: 'bg-warning',label: '😐 Weak' },
    { pct: '60%',  cls: 'bg-info',   label: '🙂 Fair' },
    { pct: '80%',  cls: 'bg-primary',label: '😊 Good' },
    { pct: '100%', cls: 'bg-success',label: '💪 Strong' },
  ];

  bar.style.width  = levels[strength].pct;
  bar.className    = 'progress-bar ' + levels[strength].cls;
  lbl.textContent  = levels[strength].label;
});

document.getElementById('confirmPassword').addEventListener('input', function () {
  const pass    = document.getElementById('regPassword').value;
  const confirm = this.value;
  const msg     = document.getElementById('passwordMatchMsg');

  if (confirm === '') {
    msg.textContent = '';
  } else if (pass === confirm) {
    msg.innerHTML  = '✅ Passwords match';
    msg.className  = 'form-text text-success';
  } else {
    msg.innerHTML  = '❌ Passwords do not match';
    msg.className  = 'form-text text-danger';
  }
});

document.getElementById('registerForm').addEventListener('submit', function (e) {
  let valid = true;

  const username  = document.getElementById('usernameInput');
  const email     = document.getElementById('regEmail');
  const password  = document.getElementById('regPassword');
  const confirm   = document.getElementById('confirmPassword');


  if (username.value.trim() === '') {
    document.getElementById('usernameError').classList.remove('d-none');
    username.classList.add('is-invalid');
    valid = false;
  } else {
    document.getElementById('usernameError').classList.add('d-none');
    username.classList.remove('is-invalid');
  }


  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email.value.trim())) {
    document.getElementById('regEmailError').classList.remove('d-none');
    email.classList.add('is-invalid');
    valid = false;
  } else {
    document.getElementById('regEmailError').classList.add('d-none');
    email.classList.remove('is-invalid');
  }


  if (password.value !== confirm.value || password.value.length < 6) {
    valid = false;
  }

  if (!valid) e.preventDefault();

  if (valid) {
    const btn = document.getElementById('regBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';
    btn.disabled = true;
  }
});
</script>
</body>
</html>
