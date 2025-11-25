<?php
$blood = strtoupper(trim($_POST['blood'] ?? ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Search Results | Blood Donor Finder</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" 
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    table {
        width: 90%;
        margin: auto;
        margin-top: 20px;
        border-collapse: collapse;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        background: white;
    }
    th {
        background: #8B0000;
        color: white;
        padding: 12px;
        letter-spacing: 0.5px;
    }
    td {
        padding: 10px;
        border: 1px solid #eee;
        text-align: center;
    }
    tr:hover {
        background: #f8f8f8;
    }
</style>
</head>

<body>

<nav class="navbar">
  <div class="logo"><i class="fa-solid fa-droplet"></i> Blood Donor Finder</div>
  <div class="nav-links">
    <a href="index.html">Home</a>
    <a href="register.html">Register</a>
    <a href="login.html">Login</a>
    <a href="search.html">Search</a>
    <a href="request.html">Request</a>
    <a href="profile.html">Profile</a>
    <a href="availability.html">Availability</a>
    <a href="admin.html">Admin</a>
    <a href="history.html">Record</a>
    <a href="viewhistory.php">History</a>
  </div>
</nav>

<h2 style="text-align:center;color:#8B0000;margin-top:20px;">
    Available Donors for <?= htmlspecialchars($blood) ?>
</h2>

<?php
$file = "donors.txt";
$found = false;

echo "<table>";
echo "<tr>
        <th>Name</th>
        <th>Email</th>
        <th>Blood Group</th>
        <th>Status</th>
        <th>Verification</th>
      </tr>";

if (file_exists($file)) {
    $f = fopen($file, "r");

    while (!feof($f)) {
        $line = trim(fgets($f));
        if ($line == "") continue;

        $parts = explode(",", $line);

        $name  = $parts[0] ?? "";
        $email = $parts[1] ?? "";
        $bg    = strtoupper(trim($parts[3] ?? ""));  // group in index 3
        $verified = $parts[5] ?? "0";
        $available = $parts[6] ?? "0";

        if ($bg == $blood && $verified == "1" && $available == "1") {
            $found = true;

            echo "<tr>
                    <td>$name</td>
                    <td><a href='view_donor_details.php?email=$email'>$email</a></td>
                    <td>$bg</td>
                    <td>" . ($available == "1" ? "Available" : "Not Available") . "</td>
                    <td>" . ($verified == "1" ? "Verified" : "Unverified") . "</td>
                  </tr>";
        }
    }

    fclose($f);
}

echo "</table>";

if (!$found) {
    echo "<p style='text-align:center;color:red;margin-top:20px;'>
            No verified & available donors found.
          </p>";
}
?>

<div style="text-align:center;margin-top:20px;">
  <a href="search.html">Search Again</a>
</div>

</body>
</html>
