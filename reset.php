<?php
$email = $_POST['email'];
$newpass = $_POST['newpass'];

$file = "donors.txt";
$temp = "temp.txt";
$found = false;

$fr = fopen($file, "r");
$fw = fopen($temp, "w");

while(!feof($fr)) {
    $line = trim(fgets($fr));
    if($line == "") continue;
    $parts = explode(",", $line);

    if(count($parts) >= 4 && trim($parts[1]) == $email) {
        $parts[3] = $newpass; // replace password
        $found = true;
    }

    fwrite($fw, implode(",", $parts) . "\n");
}

fclose($fr);
fclose($fw);

if($found) {
    rename($temp, $file);
    echo "<h3 style='color:green; text-align:center;'>Password reset successful!</h3>";
} else {
    unlink($temp);
    echo "<h3 style='color:red; text-align:center;'>Email not found in donor list.</h3>";
}

echo "<div style='text-align:center; margin-top:20px;'>
<a href='login.html'>Go to Login</a></div>";
?>
