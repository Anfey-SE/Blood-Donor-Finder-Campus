<nav class="navbar">
  <div class="logo"><i class="fa-solid fa-droplet"></i> Blood Donor Finder</div>

  <div class="nav-links">
    <a href="index.html">Home</a>
    <a href="register.html">Register</a>
    <a href="login.html">Login</a>
    <a href="search.html">Search</a>
    <a href="request.html">Request</a>
    <a href="profile.html">Profile</a>
    <a href="reset.html">Reset</a>
    <a href="availability.html">Availability</a>
    <a href="admin.html">Admin</a>
    <a href="history.html">Record</a>
    <a href="viewhistory.php">History</a>
  </div>
</nav>

<?php
$reqEmail = trim($_POST['requester_email'] ?? '');
$donEmail = trim($_POST['donor_email'] ?? '');
$rating = intval($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

if ($reqEmail==''||$donEmail==''||$rating<1||$rating>5) { echo "<h3>Invalid input.</h3><a href='rate_donor.html'>Back</a>"; exit; }

$f=fopen('ratings.txt','a');
fputcsv($f, ['r_'.time().rand(10,99), $reqEmail, $donEmail, $rating, $comment, time()]);
fclose($f);

// update donor average rating (simple re-calc)
$src='donors.txt'; $tmp=sys_get_temp_dir().'/don_rate_'.time().'.csv';
$fr=fopen($src,'r'); $fw=fopen($tmp,'w');
while(($line=fgets($fr))!==false){
    $line=trim($line); if($line==='') continue;
    $cols=str_getcsv($line); for($i=0;$i<11;$i++) if(!isset($cols[$i])) $cols[$i]=($i===4?'':'none');
    if (strcasecmp(trim($cols[1]), $donEmail)===0) {
        // compute avg from ratings.txt
        $sum=0; $count=0;
        if (file_exists('ratings.txt')) {
            $rf=fopen('ratings.txt','r');
            while(($rl=fgets($rf))!==false){ $rcols=str_getcsv($rl); if(isset($rcols[2]) && strcasecmp($rcols[2],$donEmail)===0){ $sum += intval($rcols[3]??0); $count++; } }
            fclose($rf);
        }
        $cols[10] = $count? round($sum/$count,2) : 0;
    }
    fputcsv($fw,$cols);
}
fclose($fr); fclose($fw); rename($tmp,$src);

echo "<h3 style='color:green;text-align:center;'>Thank you for rating.</h3><div style='text-align:center;margin-top:18px;'><a href='index.html'>Home</a></div>";
