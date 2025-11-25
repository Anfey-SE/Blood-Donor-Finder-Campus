<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.html'); exit;
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    echo "<h3>Please enter both username and password.</h3><a href='admin.html'>Back</a>"; exit;
}

// set your admin credentials here
$ADMIN_USER = 'admin';
$ADMIN_PASS = '12345';

if ($username === $ADMIN_USER && $password === $ADMIN_PASS) {
    $_SESSION['is_admin'] = true;
    $_SESSION['admin_user'] = $username;
    header('Location: admin_dashboard.php'); exit;
} else {
    echo "<h3 style='color:red;text-align:center;'>Invalid admin credentials.</h3><div style='text-align:center;margin-top:18px;'><a href='admin.html'>Try Again</a></div>";
    exit;
}
?>
