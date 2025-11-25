<?php
session_start();
$donors = file_exists('donors.txt') ? file('donors.txt', FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) : [];
$total = count($donors);
$byGroup = []; $verified=0; $available=0;
foreach ($donors as $line) {
    $cols = str_getcsv($line);
    for ($i=0;$i<11;$i++) if (!isset($cols[$i])) $cols[$i] = ($i===4? '': 'none');
    $bg = strtoupper(trim($cols[3] ?? 'UNKNOWN'));
    if ($bg === '') $bg = 'UNKNOWN';
    if (!isset($byGroup[$bg])) $byGroup[$bg] = 0;
    $byGroup[$bg]++;
    if (trim($cols[5] ?? '0') === '1') $verified++;
    if (trim($cols[6] ?? '0') === '1') $available++;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Statistics</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .stats-wrap{max-width:1000px;margin:18px auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px}
    .stat-card{background:#fff;border-radius:10px;padding:16px;box-shadow:0 6px 18px rgba(0,0,0,0.06);text-align:center}
    .stat-card h3{color:#8B0000;margin:0 0 8px}
    table{width:90%;margin:18px auto;border-collapse:collapse}
    th,td{padding:8px;border:1px solid #eee;text-align:left}
  </style>
</head>
<body>

<?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']===true): ?>
<nav class="navbar">
  <div class="logo"><i class="fa-solid fa-shield-halved"></i> Admin Panel</div>
  <div class="nav-links">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="announcements.php">Announcements</a>
    <a href="approve_requests.php">Approve Requests</a>
    <a href="view_donor_details.php">Donor Details</a>
    <a href="statistics.php">Statistics</a>
    <a href="recent_requests.php">Recent Requests</a>
    <a href="index.html">Logout</a>
  </div>
</nav>
<?php else: ?>
<nav class="navbar">
  <div class="logo"><i class="fa-solid fa-droplet"></i> Blood Donor Finder</div>
  <div class="nav-links">
    <a href="index.html">Home</a>
    <a href="search.html">Search Donors</a>
    <a href="filter_donors.html">Filter Donors</a>
    <a href="request.html">Request Blood</a>
    <a href="profile.php">My Profile</a>
  </div>
</nav>
<?php endif; ?>

<h2 style="text-align:center;color:#8B0000;margin-top:18px;">Campus Blood Statistics</h2>
<div class="stats-wrap">
  <div class="stat-card"><h3>Total donors</h3><p style="font-size:22px;"><?php echo $total; ?></p></div>
  <div class="stat-card"><h3>Verified donors</h3><p style="font-size:22px;"><?php echo $verified; ?></p></div>
  <div class="stat-card"><h3>Available donors</h3><p style="font-size:22px;"><?php echo $available; ?></p></div>
  <div class="stat-card"><h3>Groups</h3><p style="font-size:16px;">Breakdown below</p></div>
</div>

<h3 style="text-align:center;margin-top:10px;">Donors by Blood Group</h3>
<table><tr><th>Group</th><th>Count</th></tr>
<?php foreach ($byGroup as $g => $c): ?>
  <tr><td><?php echo htmlspecialchars($g); ?></td><td><?php echo $c; ?></td></tr>
<?php endforeach; ?>
</table>

<div style="text-align:center;margin-top:18px;"><a href="<?php echo (isset($_SESSION['is_admin'])? 'admin_dashboard.php':'index.html'); ?>">Back</a></div>
</body>
</html>
