<?php
// notifications.php - simple viewer for notifications.txt (for a user)
$email = trim($_GET['email'] ?? '');
if ($email === '') { echo "<form method='get' style='text-align:center;margin-top:30px;'><input type='email' name='email' placeholder='Your email' required> <button>View</button></form>"; exit; }
if (!file_exists('notifications.txt')) { echo "<p style='text-align:center;'>No notifications.</p>"; exit; }
$fr=fopen('notifications.txt','r');
echo "<h2 style='text-align:center;'>Notifications for ".htmlspecialchars($email)."</h2>";
echo "<ul style='max-width:800px;margin:auto;'>";
while(($line=fgets($fr))!==false){
    $cols=str_getcsv(trim($line));
    if (isset($cols[1]) && strcasecmp($cols[1], $email)===0) {
        echo "<li>".htmlspecialchars($cols[2]??'')." <small>(".date('Y-m-d H:i', intval($cols[3]??time())).")</small></li>";
    }
}
fclose($fr);
echo "</ul><div style='text-align:center;'><a href='index.html'>Home</a></div>";
