<?php
echo "<h2 style='text-align:center;color:#8B0000;'>Announcements</h2>";
if (!file_exists('announcements.txt')) { echo "<p style='text-align:center;'>No announcements.</p>"; exit; }
$fr=fopen('announcements.txt','r');
echo "<ul style='max-width:800px;margin:20px auto;'>";
while(($line=fgets($fr))!==false){
    $cols=str_getcsv(trim($line));
    echo "<li><strong>".htmlspecialchars($cols[1]??'')."</strong> - ".htmlspecialchars($cols[2]??'')." <small>(".date('Y-m-d', $cols[4]??time()).")</small></li>";
}
fclose($fr);
echo "</ul><div style='text-align:center;'><a href='index.html'>Home</a></div>";
