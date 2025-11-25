<?php
// mark_completed.php - donor marks matched request as completed by request ID
$id = trim($_POST['request_id'] ?? '');
$donorEmail = trim($_POST['donor_email'] ?? '');
if ($id=='' || $donorEmail=='') { echo "<h3>Provide request ID and donor email.</h3><a href='recent_requests.php'>Back</a>"; exit; }

$src='requests.txt'; $tmp=sys_get_temp_dir().'/req_comp_'.time().'.csv';
$fr=fopen($src,'r'); $fw=fopen($tmp,'w'); $found=false;
while(($line=fgets($fr))!==false){
    $line=trim($line); if($line==='') continue;
    $cols=str_getcsv($line);
    if (($cols[0]??'') === $id) {
        $cols[7] = 'closed';
        $cols[8] = $donorEmail; // matched donor id/email
        $found=true;
    }
    fputcsv($fw,$cols);
}
fclose($fr); fclose($fw);
if ($found) { rename($tmp,$src); echo "<h3 style='color:green;text-align:center;'>Marked completed.</h3>"; }
else { unlink($tmp); echo "<h3 style='color:red;text-align:center;'>Request not found.</h3>"; }
echo "<div style='text-align:center;margin-top:18px;'><a href='recent_requests.php'>Back</a></div>";
