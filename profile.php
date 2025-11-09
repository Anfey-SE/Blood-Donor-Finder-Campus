<?php
$email = $_POST['email'];
$newblood = $_POST['newblood'];
$newpassword = $_POST['newpassword'];

$file = "donors.txt";
$temp = "temp.txt";

$updated = false;

$fr = fopen($file, "r");
$fw = fopen($temp, "w");

while(!feof($fr)) {
    $line = trim(fgets($fr));
    if($line == "") continue;

    $parts = explode(",", $line);

    if(count($parts) >= 4 && trim($parts[1]) == $email) {
        // Update donor details
        $parts[2] = $newblood;
        $parts[3] = $newpassword;
        $updated = true;
    }

    fwrite($fw, implode(",", $parts) . "\n");
}

fclose($fr);
fclose($fw);

if($updated) {
    rename($temp, $file);
    echo "<h3 style='color:green; text-align:center;'>Profile updated successfully!</h3>";
} else {
    unlink($temp);
    echo "<h3 style='color:red; text-align:center;'>Email not found in donor list.</h3>";
}

echo "<div style='text-align:center; margin-top:20px;'>
<a href='index.html'>Back to Home</a></div>";
?>
