<?php
session_start();
if (!isset($_SESSION['is_admin'])) {
    header("Location: index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Recent Requests | Admin Panel</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    .page-container { max-width:1100px; margin:40px auto; background:#fff; padding:25px; border-radius:12px; box-shadow:0 6px 15px rgba(0,0,0,0.1); }
    table { width:100%; border-collapse:collapse; margin-top:20px; }
    th, td { border:1px solid #ccc; padding:10px; text-align:center; }
    th { background:#8B0000; color:#fff; }
    h2 { text-align:center; color:#8B0000; }
  </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="logo"><i class="fa-solid fa-shield-halved"></i> Admin Panel</div>
  <div class="nav-links">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="announcements.php">Announcements</a>
    <a href="approve_requests.php">Approve Requests</a>
    <a href="view_donor_details.php">Donor Details</a>
    <a href="statistics.php">Statistics</a>
    <a class="active" href="recent_requests.php">Recent Requests</a>
    <a href="logout.php">Logout</a>
  </div>
</nav>

<div class="page-container">
<h2>Recent Requests</h2>

<?php
if (!file_exists('requests.txt')) {
    echo "<p style='text-align:center;'>No requests yet.</p>";
} else {

    $rows = file('requests.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $rows = array_reverse($rows);

    echo "<table>";
    echo "<tr>
            <th>Time</th>
            <th>Requester</th>
            <th>Blood Group</th>
            <th>Dept / Hostel</th>
            <th>Details</th>
            <th>Status</th>
          </tr>";

    foreach ($rows as $line) {
        $cols = str_getcsv($line);

        echo "<tr>";
        echo "<td>" . (isset($cols[9]) ? date('Y-m-d H:i', $cols[9]) : '') . "</td>";
        echo "<td>" . htmlspecialchars(($cols[1] ?? '') . ' (' . ($cols[2] ?? '') . ')') . "</td>";
        echo "<td>" . htmlspecialchars($cols[3] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars(($cols[4] ?? '') . " / " . ($cols[5] ?? '')) . "</td>";
        echo "<td>" . htmlspecialchars($cols[6] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($cols[7] ?? '') . "</td>";
        echo "</tr>";
    }

    echo "</table>";
}
?>
</div>

<footer>
  <p style="text-align:center; margin:40px 0 20px;">© 2025 Campus Blood Donor Finder</p>
</footer>

</body>
</html>
