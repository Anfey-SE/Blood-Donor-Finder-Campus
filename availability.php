<?php
// update availability flag in donors.txt (index 6)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: availability.html');
    exit;
}

$email = trim($_POST['email'] ?? '');
$status = trim($_POST['status'] ?? '');

if ($email === '' || $status === '') {
    echo "<h3>Please provide email and status.</h3><a href='availability.html'>Back</a>";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<h3>Invalid email.</h3><a href='availability.html'>Back</a>";
    exit;
}

$val = ($status === 'available') ? '1' : '0';

$src = 'donors.txt';
if (!file_exists($src)) {
    echo "<h3>No donors found.</h3><a href='register.html'>Register</a>";
    exit;
}

$tmp = sys_get_temp_dir() . '/donors_tmp_' . time() . '_' . rand(1000,9999) . '.csv';
$fr = fopen($src, 'r');
$fw = fopen($tmp, 'w');
$found = false;

while (($line = fgets($fr)) !== false) {
    $line = trim($line);
    if ($line === '') continue;

    $cols = str_getcsv($line);
    for ($i=0; $i<11; $i++) if (!isset($cols[$i])) $cols[$i] = ($i === 4 ? '' : 'none');

    if (isset($cols[1]) && strcasecmp(trim($cols[1]), $email) === 0) {
        $cols[6] = $val; // available field
        $found = true;
    }

    fputcsv($fw, $cols);
}

fclose($fr);
fclose($fw);

if ($found) {
    if (!rename($tmp, $src)) {
        $data = file_get_contents($tmp);
        file_put_contents($src, $data, LOCK_EX);
        unlink($tmp);
    }
    $label = ($val === '1') ? 'Available' : 'Unavailable';
    echo "<h3 style='color:green; text-align:center;'>Status updated to <u>$label</u>.</h3>";
} else {
    unlink($tmp);
    echo "<h3 style='color:red; text-align:center;'>Email not found.</h3>";
}

echo "<div style='text-align:center; margin-top:20px;'><a href='index.html'>Back to Home</a></div>";
exit;
?>
