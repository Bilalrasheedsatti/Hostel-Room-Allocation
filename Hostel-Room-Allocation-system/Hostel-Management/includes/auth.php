<?php
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function redirect_to_dashboard() {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: student/dashboard.php");
    }
    exit;
}

function require_login() {
    if (!is_logged_in()) {
        header("Location: ../login.php");
        exit;
    }
}

function require_admin() {
    require_login();
    if ($_SESSION['role'] !== 'admin') {
        header("Location: ../student/dashboard.php");
        exit;
    }
}

function require_student() {
    require_login();
    if ($_SESSION['role'] !== 'student') {
        header("Location: ../admin/dashboard.php");
        exit;
    }
}
?>