<div class="admin-header">Admin Panel</div>

<nav class="admin-nav">
  <ul>
    <li><a href="admin_dashboard.php">Dashboard</a></li>
    <li><a href="announcements.php">Announcements</a></li>
    <li><a href="approve_requests.php">Approve Requests</a></li>
    <li><a href="statistics.php">Statistics</a></li>
    <li><a href="recent_requests.php">Recent Requests</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</nav>

<?php
// enable_email.php - donor toggles email notifications
$email = trim($_POST['email'] ?? '');
$enable = isset($_POST['enable']) ? '1' : '0';
if ($email === '') { echo "<h3>Provide email.</h3><a href='profile.html'>Back</a>"; exit; }

$src='donors.txt'; $tmp=sys_get_temp_dir().'/eemail_'.time().'.csv';
$fr=fopen($src,'r'); $fw=fopen($tmp,'w'); $found=false;
while(($line=fgets($fr))!==false){
    $line=trim($line); if($line==='') continue;
    $cols=str_getcsv($line);
    for($i=0;$i<11;$i++) if(!isset($cols[$i])) $cols[$i]=($i===4?'':'none');
    if (strcasecmp(trim($cols[1]), $email)===0) { $cols[9] = $enable; $found=true; }
    fputcsv($fw,$cols);
}
fclose($fr); fclose($fw);
if ($found) { rename($tmp,$src); echo "<h3 style='color:green;text-align:center;'>Preferences updated.</h3>"; }
else { unlink($tmp); echo "<h3 style='color:red;text-align:center;'>Email not found.</h3>"; }
echo "<div style='text-align:center;margin-top:18px;'><a href='profile.html'>Back</a></div>";
