<?php
$name = $_POST['name'];
$email = $_POST['email'];
$blood = strtoupper(trim($_POST['blood']));
$location = $_POST['location'];

// 1️⃣  Save request
$file = fopen("requests.txt","a");
fwrite($file,"$name,$email,$blood,$location\n");
fclose($file);

echo "<h2 style='text-align:center;color:#8B0000;'>Blood Request Submitted!</h2>";
echo "<p style='text-align:center;'>We’re now checking for matching donors...</p>";

// 2️⃣  Notify matching donors (simulate)
$donorFile = "donors.txt";
if(file_exists($donorFile)){
    $f = fopen($donorFile,"r");
    $found = false;
    echo "<table border='1' style='margin:auto;border-collapse:collapse;width:80%;text-align:center;'>";
    echo "<tr style='background-color:#8B0000;color:white;'>
            <th>Donor Name</th><th>Email</th><th>Blood Group</th><th>Status</th><th>Verification</th>
          </tr>";
    while(!feof($f)){
        $line = trim(fgets($f));
        if($line=="") continue;
        $parts = explode(",",$line);

        $nameD  = $parts[0];
        $emailD = $parts[1];
        $bg     = strtoupper(trim($parts[2]));
        $status = isset($parts[4]) ? trim($parts[4]) : "available";
        $verify = isset($parts[5]) ? trim($parts[5]) : "unverified";

        // Only show available and verified donors with same group
        if($bg == $blood && $status == "available" && $verify == "verified"){
            echo "<tr>
                    <td>$nameD</td>
                    <td>$emailD</td>
                    <td>$bg</td>
                    <td>$status</td>
                    <td>$verify</td>
                  </tr>";
            $found = true;
        }
    }
    fclose($f);
    echo "</table>";

    if(!$found)
        echo "<p style='text-align:center;color:red;'>No verified, available donors found for this blood group.</p>";
}else{
    echo "<p style='text-align:center;color:red;'>No donor database found.</p>";
}

echo "<div style='text-align:center;margin-top:20px;'>
<a href='index.html'>Back to Home</a></div>";
?>
