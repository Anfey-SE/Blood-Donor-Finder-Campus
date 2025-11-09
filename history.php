<?php
$donor = $_POST['donor'];
$email = $_POST['email'];
$blood = $_POST['blood'];
$recipient = $_POST['recipient'];
$location = $_POST['location'];
$date = date("Y-m-d");

$file = fopen("history.txt", "a");
fwrite($file, "$donor,$email,$blood,$recipient,$location,$date\n");
fclose($file);

echo "<h3 style='color:green; text-align:center;'>Donation record saved successfully!</h3>";
echo "<div style='text-align:center; margin-top:20px;'>
<a href='viewhistory.php'>View All Donations</a></div>";
?>
