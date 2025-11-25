<nav class="navbar">
  <div class="logo"><i class="fa-solid fa-droplet"></i> Blood Donor Finder</div>

  <div class="nav-links">
    <a href="index.html">Home</a>
    <a href="register.html">Register</a>
    <a href="login.html">Login</a>
    <a href="search.html">Search</a>
    <a href="request.html">Request</a>
    <a href="profile.html">Profile</a>
    <a href="reset.html">Reset</a>
    <a href="availability.html">Availability</a>
    <a href="admin.html">Admin</a>
    <a href="history.html">Record</a>
    <a href="viewhistory.php">History</a>
  </div>
</nav>

<?php
$email = trim($_POST['email'] ?? '');
$newpass = trim($_POST['newpass'] ?? '');

if ($email === '' || $newpass === '') {
    echo "<h3 style='color:red; text-align:center;'>All fields required.</h3>";
    echo "<div style='text-align:center; margin-top:20px;'>
            <a href='reset.html'>Back</a></div>";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<h3 style='color:red; text-align:center;'>Invalid email format.</h3>";
    exit;
}

$hash = password_hash($newpass, PASSWORD_DEFAULT);

$src = "donors.txt";
$tmp = "temp.txt";

$fr = fopen($src, "r");
$fw = fopen($tmp, "w");

$found = false;

while (($line = fgets($fr)) !== false) {
    $line = trim($line);
    if ($line === "") continue;

    $cols = str_getcsv($line);

    // pad to 11 fields
    for ($i=0;$i<11;$i++) if (!isset($cols[$i])) $cols[$i] = "none";

    if (trim($cols[1]) === $email) {
        $cols[4] = $hash;  // new hashed password
        $found = true;
    }

    fputcsv($fw, $cols);
}

fclose($fr);
fclose($fw);

if ($found) {
    rename($tmp, $src);
    echo "<h3 style='color:green; text-align:center;'>Password reset successful!</h3>";
} else {
    unlink($tmp);
    echo "<h3 style='color:red; text-align:center;'>Email not found in donor list.</h3>";
}

echo "<div style='text-align:center; margin-top:20px;'>
<a href='login.html'>Go to Login</a></div>";
?>
