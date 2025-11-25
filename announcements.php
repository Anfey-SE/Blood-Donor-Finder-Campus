<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin.html'); exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: announcements.html'); exit;
}

$title = trim($_POST['title'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($title === '' || $message === '') {
    echo "<h3>All fields required.</h3><a href='announcements.html'>Back</a>"; exit;
}

$file = fopen('announcements.txt', 'a');
if ($file) {
    fputcsv($file, ['ann_' . time() . rand(10,99), $title, $message, $_SESSION['admin_user'] ?? 'admin', time()]);
    fclose($file);
    echo "<h3 style='color:green;text-align:center;'>Announcement posted.</h3>";
    echo "<div style='text-align:center;margin-top:16px;'><a href='admin_dashboard.php'>Back to Dashboard</a></div>";
    exit;
} else {
    echo "<h3 style='color:red;text-align:center;'>Failed to save announcement.</h3><a href='admin_dashboard.php'>Back</a>";
    exit;
}
?>
