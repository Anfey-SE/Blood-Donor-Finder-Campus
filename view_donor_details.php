<?php
session_start();
$email = trim($_GET['email'] ?? '');
if ($email === '') {
    // show a small search form (so admin can enter email) instead of immediate error
    ?>
    <!doctype html>
    <html><head><meta charset="utf-8"><title>Donor Details</title><link rel="stylesheet" href="style.css"></head><body>
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
    <section class="form-section">
      <h2>Find Donor</h2>
      <form method="get" action="view_donor_details.php">
        <input type="email" name="email" placeholder="Donor email" required>
        <button type="submit">Search</button>
      </form>
    </section>
    </body></html>
    <?php
    exit;
}

if (!file_exists('donors.txt')) {
    echo "<h3>No donors file.</h3><a href='index.html'>Home</a>"; exit;
}

$found = false;
$fr = fopen('donors.txt','r');
while (($line = fgets($fr)) !== false) {
    $line = trim($line);
    if ($line === '') continue;
    $cols = str_getcsv($line);
    for ($i=0;$i<11;$i++) if (!isset($cols[$i])) $cols[$i] = ($i===4? '': 'none');
    if (isset($cols[1]) && strcasecmp(trim($cols[1]), $email) === 0) {
        $found = true;
        $name = $cols[0];
        $dept = $cols[2];
        $blood = $cols[3];
        $hostel = $cols[7];
        $verified = $cols[5] ?? '0';
        $lastDonation = $cols[8] ?? 'none';
        $emailContact = $cols[1];
        break;
    }
}
fclose($fr);

if (!isset($_SESSION['is_admin'])) {
    // non-admin should not see contact unless verified (you can adjust)
    $showContact = ($verified === '1');
} else {
    $showContact = true;
}

?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Donor Details</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .donor-card { max-width:720px; margin:28px auto; background:#fff; padding:20px; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.06); border-top:5px solid #8B0000; }
    .donor-card h2 { color:#8B0000; margin:0 0 8px; }
    .donor-row { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #f0f0f0; }
    .label { font-weight:600; color:#555; width:140px; }
    .value { color:#222; flex:1; }
    .contact { text-align:center; margin-top:14px; }
  </style>
</head>
<body>

<?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']===true): ?>
<nav class="navbar">
  <div class="logo"><i class="fa-solid fa-shield-halved"></i> Admin Panel</div>
  <div class="nav-links">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="approve_requests.php">Approve Requests</a>
    <a href="statistics.php">Statistics</a>
  </div>
</nav>
<?php else: ?>
<nav class="navbar">
  <div class="logo"><i class="fa-solid fa-droplet"></i> Blood Donor Finder</div>
  <div class="nav-links">
    <a href="index.html">Home</a>
    <a href="search.html">Search</a>
  </div>
</nav>
<?php endif; ?>

<?php if (!$found): ?>
  <h3 style="text-align:center;margin-top:20px;">Donor not found.</h3>
  <div style="text-align:center;margin-top:12px;"><a href="search.html">Back</a></div>
<?php else: ?>
  <?php if ($verified !== '1' && !isset($_SESSION['is_admin'])): ?>
    <h3 style="text-align:center;margin-top:20px;">Donor not verified. Contact is not visible.</h3>
    <div style="text-align:center;margin-top:12px;"><a href="search.html">Back</a></div>
  <?php else: ?>
    <div class="donor-card">
      <h2><?php echo htmlspecialchars($name); ?></h2>
      <div class="donor-row"><div class="label">Email</div><div class="value"><?php echo $showContact?htmlspecialchars($emailContact):'Hidden'; ?></div></div>
      <div class="donor-row"><div class="label">Department</div><div class="value"><?php echo htmlspecialchars($dept); ?></div></div>
      <div class="donor-row"><div class="label">Hostel</div><div class="value"><?php echo htmlspecialchars($hostel); ?></div></div>
      <div class="donor-row"><div class="label">Blood Group</div><div class="value"><?php echo htmlspecialchars($blood); ?></div></div>
      <div class="donor-row"><div class="label">Last Donation</div><div class="value"><?php echo htmlspecialchars($lastDonation); ?></div></div>
      <div class="contact">
        <?php if ($showContact): ?>
          <a href="mailto:<?php echo htmlspecialchars($emailContact); ?>"><button style="background:#8B0000;color:#fff;border:0;padding:10px 14px;border-radius:8px;">Contact Donor</button></a>
        <?php else: ?>
          <p style="color:#666">Contact hidden for unverified donors.</p>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
<?php endif; ?>

<div style="text-align:center;margin-top:18px;"><a href="<?php echo (isset($_SESSION['is_admin'])?'admin_dashboard.php':'search.html'); ?>">Back</a></div>
</body>
</html>
