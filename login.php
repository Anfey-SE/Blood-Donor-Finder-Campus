<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    echo "<h3>Please enter email and password.</h3><a href='login.html'>Go Back</a>";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<h3>Invalid email format.</h3><a href='login.html'>Go Back</a>";
    exit;
}

$donorsFile = 'donors.txt';
if (!file_exists($donorsFile)) {
    echo "<h3>No users found. Please register first.</h3><a href='register.html'>Register</a>";
    exit;
}

$fp = fopen($donorsFile, 'r');
$found = false;
$record = null;
if ($fp) {
    while (($line = fgets($fp)) !== false) {
        $line = trim($line);
        if ($line === '') continue;
        $cols = str_getcsv($line);
        if (isset($cols[1]) && strcasecmp(trim($cols[1]), $email) === 0) {
            $record = $cols;
            $found = true;
            break;
        }
    }
    fclose($fp);
}

if (!$found) {
    echo "<h3>Email not registered.</h3><a href='register.html'>Register</a>";
    exit;
}

// record field indexes:
// 0:name,1:email,2:department,3:blood,4:passwordHashOrPlain,5:verified,6:available,7:hostel,8:lastDonation,9:emailEnabled,10:rating
$stored = $record[4] ?? '';
$verified = $record[5] ?? '0';
$name = $record[0] ?? '';

$auth_ok = false;
if (password_get_info($stored)['algo'] !== 0) {
    // stored looks like a hash
    if (password_verify($password, $stored)) $auth_ok = true;
} else {
    // stored plain (old entries) - accept plain match
    if ($password === $stored) $auth_ok = true;
}

if (!$auth_ok) {
    echo "<h3>Incorrect password.</h3><a href='login.html'>Try Again</a>";
    exit;
}

if ($verified !== '1') {
    echo "<h3>Your account is pending admin verification.</h3><a href='index.html'>Home</a>";
    exit;
}

// login success
$_SESSION['user_email'] = $email;
$_SESSION['user_name'] = $name;
$_SESSION['logged_in'] = true;

header('Location: profile.php');
exit;
?>
