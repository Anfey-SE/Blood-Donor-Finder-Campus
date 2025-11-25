<?php
// cancel_request.php - requester cancels their request by ID or email
$id = trim($_POST['request_id'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($id === '' && $email === '') {
    echo "<h3>Provide request ID or your email.</h3><a href='request_history.php'>Back</a>"; exit;
}

$src='requests.txt'; $tmp=sys_get_temp_dir().'/req_tmp_'.time().'.csv';
$fr=fopen($src,'r'); $fw=fopen($tmp,'w'); $found=false;
while(($line=fgets($fr))!==false){
    $line=trim($line); if($line==='') continue;
    $cols=str_getcsv($line);
    if (($id !== '' && ($cols[0]??'') === $id) || ($email !== '' && strcasecmp($cols[2]??'',$email)===0)) {
        // mark cancelled
        $cols[7] = 'cancelled';
        $found=true;
    }
    fputcsv($fw,$cols);
}
fclose($fr); fclose($fw);
if($found){ rename($tmp,$src); echo "<h3 style='color:green;text-align:center;'>Request cancelled.</h3>"; }
else { unlink($tmp); echo "<h3 style='color:red;text-align:center;'>No matching request found.</h3>"; }
echo "<div style='text-align:center;margin-top:18px;'><a href='request_history.php'>Back</a></div>";
