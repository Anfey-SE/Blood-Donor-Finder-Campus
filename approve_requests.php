<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin.html'); exit;
}

$src = 'donors.txt';

// Handle POST (approve/reject)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $action = $_POST['action'] ?? '';

    if ($email === '' || ($action !== 'approve' && $action !== 'reject')) {
        echo "<h3>Invalid request.</h3><a href='approve_requests.php'>Back</a>"; exit;
    }

    if (!file_exists($src)) { echo "<h3>No donors file.</h3><a href='approve_requests.php'>Back</a>"; exit; }

    $tmp = sys_get_temp_dir() . '/don_tmp_' . time() . '_' . rand(1000,9999) . '.csv';
    $fr = fopen($src, 'r'); $fw = fopen($tmp, 'w'); $found = false;

    while (($line = fgets($fr)) !== false) {
        $line = trim($line);
        if ($line === '') continue;
        $cols = str_getcsv($line);
        for ($i=0;$i<11;$i++) if (!isset($cols[$i])) $cols[$i] = ($i===4 ? '' : 'none');
        if (strcasecmp(trim($cols[1]), $email) === 0) {
            $found = true;
            if ($action === 'approve') {
                $cols[5] = '1'; // mark verified
                fputcsv($fw, $cols);
            } else {
                // reject -> skip (remove)
                // optionally log rejects to a file
            }
        } else {
            fputcsv($fw, $cols);
        }
    }
    fclose($fr); fclose($fw);

    if ($found) {
        rename($tmp, $src);
        echo "<h3 style='color:green;text-align:center;'>Action completed.</h3>";
        echo "<div style='text-align:center;margin-top:12px;'><a href='approve_requests.php'>Back</a></div>";
    } else {
        unlink($tmp);
        echo "<h3 style='color:red;text-align:center;'>Email not found.</h3><a href='approve_requests.php'>Back</a>";
    }
    exit;
}

// GET: show pending donors
$pending = [];
if (file_exists($src)) {
    $fr = fopen($src, 'r');
    while (($line = fgets($fr)) !== false) {
        $line = trim($line);
        if ($line === '') continue;
        $cols = str_getcsv($line);
        for ($i=0;$i<11;$i++) if (!isset($cols[$i])) $cols[$i] = ($i===4 ? '' : 'none');
        if (trim($cols[5] ?? '0') !== '1') {
            $pending[] = ['name'=>$cols[0],'email'=>$cols[1],'dept'=>$cols[2],'blood'=>$cols[3]];
        }
    }
    fclose($fr);
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Approve Registrations</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .pending-table{max-width:1000px;margin:18px auto;border-collapse:collapse;width:95%}
    .pending-table th, .pending-table td{padding:10px;border:1px solid #eee;text-align:left}
    .action-form { display:inline-block; margin:0 6px; }
    .note {text-align:center;color:#666}
  </style>
</head>
<body>
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

<h2 style="text-align:center;color:#8B0000;margin-top:18px;">Pending Registrations</h2>
<?php if (empty($pending)): ?>
  <p class="note">No pending registrations.</p>
<?php else: ?>
  <table class="pending-table">
    <tr><th>Name</th><th>Email</th><th>Dept</th><th>Blood</th><th>Action</th></tr>
    <?php foreach ($pending as $p): ?>
      <tr>
        <td><?php echo htmlspecialchars($p['name']); ?></td>
        <td><?php echo htmlspecialchars($p['email']); ?></td>
        <td><?php echo htmlspecialchars($p['dept']); ?></td>
        <td><?php echo htmlspecialchars($p['blood']); ?></td>
        <td>
          <form class="action-form" method="post" action="approve_requests.php">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($p['email']); ?>">
            <button type="submit" name="action" value="approve">Approve</button>
          </form>
          <form class="action-form" method="post" action="approve_requests.php">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($p['email']); ?>">
            <button type="submit" name="action" value="reject">Reject</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>

<div style="text-align:center;margin-top:18px;"><a href="admin_dashboard.php">Back to Dashboard</a></div>
</body>
</html>
