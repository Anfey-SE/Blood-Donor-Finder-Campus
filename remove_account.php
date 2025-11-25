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
<?php
// remove_account.php - admin removes user by email
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: admin.html'); exit; }
$email = trim($_POST['email'] ?? '');
if ($email === '') { echo "<h3>Enter email.</h3><a href='admin.html'>Back</a>"; exit; }

$src='donors.txt'; $tmp=sys_get_temp_dir().'/rem_acc_'.time().'.csv';
$fr=fopen($src,'r'); $fw=fopen($tmp,'w'); $found=false;
while(($line=fgets($fr))!==false){
    $line=trim($line); if($line==='') continue;
    $cols=str_getcsv($line);
    if (isset($cols[1]) && strcasecmp(trim($cols[1]), $email)===0) { $found=true; continue; }
    fputcsv($fw,$cols);
}
fclose($fr); fclose($fw);
if ($found) { rename($tmp,$src); echo "<h3 style='color:green;text-align:center;'>Account removed.</h3>"; }
else { unlink($tmp); echo "<h3 style='color:red;text-align:center;'>Email not found.</h3>"; }
echo "<div style='text-align:center;margin-top:18px;'><a href='admin.html'>Back</a></div>";
