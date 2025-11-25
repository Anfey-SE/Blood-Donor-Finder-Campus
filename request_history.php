<?php
// request_history.php - requester views their past requests
$email = trim($_GET['email'] ?? '');
if ($email === '') {
    echo "<form method='get' style='text-align:center;margin-top:30px;'><input type='email' name='email' placeholder='Your email' required> <button>View</button></form>";
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { echo "<h3>Invalid email.</h3><a href='request_history.php'>Back</a>"; exit; }
if (!file_exists('requests.txt')) { echo "<p style='text-align:center;'>No requests yet.</p>"; exit; }

echo "<h2 style='text-align:center;color:#8B0000;'>Requests for " . htmlspecialchars($email) . "</h2>";
echo "<table border='1' style='margin:auto;border-collapse:collapse;width:90%'><tr><th>Date</th><th>Blood</th><th>Details</th><th>Status</th></tr>";
$fr=fopen('requests.txt','r');
while(($line=fgets($fr))!==false){
    $line=trim($line); if($line==='') continue;
    $cols=str_getcsv($line);
    if (isset($cols[2]) && strcasecmp($cols[2], $email)===0) {
        echo "<tr>";
        echo "<td>".(isset($cols[9])?date('Y-m-d H:i',$cols[9]):'')."</td>";
        echo "<td>".htmlspecialchars($cols[3]??'')."</td>";
        echo "<td>".htmlspecialchars($cols[6]??'')."</td>";
        echo "<td>".htmlspecialchars($cols[7]??'')."</td>";
        echo "</tr>";
    }
}
fclose($fr);
echo "</table>";
echo "<div style='text-align:center;margin-top:18px;'><a href='index.html'>Home</a></div>";
