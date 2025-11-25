<?php
// register.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.html');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$blood = strtoupper(trim($_POST['blood'] ?? ''));
$password = $_POST['password'] ?? '';
$department = trim($_POST['department'] ?? 'none');
$hostel = trim($_POST['hostel'] ?? 'none');

if ($name === '' || $email === '' || $blood === '' || $password === '') {
    echo "<h3>Please fill all required fields.</h3><a href='register.html'>Go Back</a>";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<h3>Invalid email format.</h3><a href='register.html'>Try Again</a>";
    exit;
}

if (!preg_match('/^(A|B|AB|O)[+-]$/i', $blood)) {
    echo "<h3>Invalid blood group. Use A+, O-, AB+, etc.</h3><a href='register.html'>Try Again</a>";
    exit;
}

if (strlen($password) < 6) {
    echo "<h3>Password must be at least 6 characters.</h3><a href='register.html'>Try Again</a>";
    exit;
}

$donorsFile = 'donors.txt';
if (!file_exists($donorsFile)) {
    touch($donorsFile);
}

// check duplicate email
$fp = fopen($donorsFile, 'r');
$exists = false;
if ($fp) {
    while (($line = fgets($fp)) !== false) {
        $line = trim($line);
        if ($line === '') continue;
        $cols = str_getcsv($line);
        if (isset($cols[1]) && strcasecmp(trim($cols[1]), $email) === 0) {
            $exists = true;
            break;
        }
    }
    fclose($fp);
}

if ($exists) {
    echo "<h3>Email already registered.</h3><a href='register.html'>Try Again</a>";
    exit;
}

// hash password
$hash = password_hash($password, PASSWORD_DEFAULT);

// create new line in 11-field format
$new = [
    $name,
    $email,
    $department === '' ? 'none' : $department,
    $blood,
    $hash,
    '0',      // verified
    '1',      // available
    $hostel === '' ? 'none' : $hostel,
    'none',   // lastDonation
    '0',      // emailEnabled
    '0'       // rating
];

$line = implode(',', array_map(function($v){
    return str_replace(["\n","\r"], ['', ''], $v);
}, $new)) . PHP_EOL;

// append with lock
file_put_contents($donorsFile, $line, FILE_APPEND | LOCK_EX);

echo "<h3>Registration successful! Await admin verification.</h3><a href='index.html'>Home</a>";
exit;
?>
