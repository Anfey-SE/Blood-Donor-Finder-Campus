<?php
$email = $_POST['email'];
$status = $_POST['status'];

$file = "donors.txt";
$temp = "temp.txt";
$found = false;

$fr = fopen($file, "r");
$fw = fopen($temp, "w");

while(!feof($fr)) {
    $line = trim(fgets($fr));
    if($line == "") continue;
    $parts = explode(",", $line);

    // If donor found, update or append status
    if(count($parts) >= 4 && trim($parts[1]) == $email) {
        if(count($parts) == 4) {
            $parts[] = $status;      // add status if not present
        } else {
            $parts[4] = $status;     // update status
        }
        $found = true;
    }
    fwrite($fw, implode(",", $parts) . "\n");
}

fclose($fr);
fclose($fw);

if($found) {
    rename($temp, $file);
    echo "<h3 style='color:green; text-align:center;'>Availability updated to <u>$status</u>.</h3>";
} else {
    unlink($temp);
    echo "<h3 style='color:red; text-align:center;'>Email not found in donor list.</h3>";
}

echo "<div style='text-align:center; margin-top:20px;'>
<a href='index.html'>Back to Home</a></div>";
?>
