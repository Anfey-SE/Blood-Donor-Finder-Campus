<?php
// update blood group and password for a donor (11-field format)

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: profile.html');
    exit;
}

$email = trim($_POST['email'] ?? '');
$newblood = strtoupper(trim($_POST['newblood'] ?? ''));
$newpassword = $_POST['newpassword'] ?? '';

// basic validation
if ($email === '' || $newblood === '' || $newpassword === '') {
    echo "<h3>Please fill all fields.</h3><a href='profile.html'>Go Back</a>";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<h3>Invalid email.</h3><a href='profile.html'>Go Back</a>";
    exit;
}

if (!preg_match('/^(A|B|AB|O)[+-]$/i', $newblood)) {
    echo "<h3>Invalid blood group. Use A+, O-, AB+ etc.</h3><a href='profile.html'>Go Back</a>";
    exit;
}

if (strlen($newpassword) < 6) {
    echo "<h3>Password must be at least 6 characters.</h3><a href='profile.html'>Go Back</a>";
    exit;
}

$src = 'donors.txt';
if (!file_exists($src)) {
    echo "<h3>No donors found.</h3><a href='register.html'>Register</a>";
    exit;
}

$tmp = sys_get_temp_dir() . '/donors_tmp_' . time() . '_' . rand(1000,9999) . '.csv';
$fr = fopen($src, 'r');
$fw = fopen($tmp, 'w');
$updated = false;

while (($line = fgets($fr)) !== false) {
    $line = trim($line);
    if ($line === '') continue;

    $cols = str_getcsv($line);

    // ensure array has 11 fields
    for ($i = 0; $i < 11; $i++) {
        if (!isset($cols[$i])) $cols[$i] = ($i === 4 ? '' : 'none'); // leave password blank if missing
    }

    if (isset($cols[1]) && strcasecmp(trim($cols[1]), $email) === 0) {
        // update blood (index 3) and password hash (index 4)
        $cols[3] = $newblood;
        $cols[4] = password_hash($newpassword, PASSWORD_DEFAULT);
        $updated = true;
    }

    // write row safely
    fputcsv($fw, $cols);
}

fclose($fr);
fclose($fw);

if ($updated) {
    // replace original file
    if (!rename($tmp, $src)) {
        // fallback: try atomic write
        $data = file_get_contents($tmp);
        file_put_contents($src, $data, LOCK_EX);
        unlink($tmp);
    }
    echo "<h3 style='color:green; text-align:center;'>Profile updated successfully!</h3>";
} else {
    unlink($tmp);
    echo "<h3 style='color:red; text-align:center;'>Email not found.</h3>";
}

echo "<div style='text-align:center; margin-top:20px;'><a href='index.html'>Back to Home</a></div>";
exit;
?>
