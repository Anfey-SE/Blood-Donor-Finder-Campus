<?php
$blood = strtoupper(trim($_POST['blood']));
$file  = "donors.txt";
$found = false;

echo "<h2 style='text-align:center;color:#8B0000;'>Available Verified Donors for $blood</h2>";
echo "<table border='1' style='margin:auto;border-collapse:collapse;width:80%;text-align:center;'>";
echo "<tr style='background-color:#8B0000;color:white;'>
        <th>Name</th><th>Email</th><th>Blood Group</th><th>Status</th><th>Verification</th>
      </tr>";

if(file_exists($file)){
  $f = fopen($file,"r");
  while(!feof($f)){
    $line = trim(fgets($f));
    if($line=="") continue;
    $parts = explode(",",$line);
    
    // name,email,blood,password,status(optional),verified(optional)
    $name  = $parts[0];
    $email = $parts[1];
    $bg    = strtoupper(trim($parts[2]));
    $status = isset($parts[4]) ? trim($parts[4]) : "available";
    $verify = isset($parts[5]) ? trim($parts[5]) : "unverified";

    if($bg == $blood && $status == "available" && $verify == "verified"){
      echo "<tr>
              <td>$name</td>
              <td>$email</td>
              <td>$bg</td>
              <td>$status</td>
              <td>$verify</td>
            </tr>";
      $found = true;
    }
  }
  fclose($f);
}
echo "</table>";

if(!$found)
  echo "<p style='text-align:center;color:red;'>No verified, available donors found.</p>";

echo "<div style='text-align:center;margin-top:20px;'>
<a href='index.html'>Back to Home</a></div>";
?>
