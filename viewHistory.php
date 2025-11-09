<?php
echo "<h2 style='text-align:center; color:#8B0000;'>Donation History</h2>";
if(file_exists("history.txt")){
  $file = fopen("history.txt", "r");
  echo "<table border='1' style='margin:auto; border-collapse:collapse; width:85%; text-align:center;'>";
  echo "<tr style='background-color:#8B0000; color:white;'>
        <th>Donor</th><th>Email</th><th>Blood Group</th>
        <th>Recipient</th><th>Location</th><th>Date</th></tr>";
  while(!feof($file)){
    $line = trim(fgets($file));
    if($line=="") continue;
    $parts = explode(",", $line);
    echo "<tr>";
    foreach($parts as $p){ echo "<td>".htmlspecialchars($p)."</td>"; }
    echo "</tr>";
  }
  echo "</table>";
  fclose($file);
}else{
  echo "<p style='text-align:center;'>No donation records yet.</p>";
}
echo "<div style='text-align:center; margin-top:20px;'>
<a href='index.html'>Back to Home</a></div>";
?>
