<?php

function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function show_success($message) {
    return '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>' . $message . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
}

function show_error($message) {
    return '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>' . $message . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
}

function get_user($conn, $user_id) {
    $user_id = (int)$user_id;
    $result  = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
    return mysqli_fetch_assoc($result);
}
?>
