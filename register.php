<?php
// basic validation
if (empty($_POST['name']) || empty($_POST['email']) ||
    empty($_POST['blood']) || empty($_POST['password'])) {
    echo "<h3>Please fill all fields.</h3><a href='register.html'>Go Back</a>";
    exit;
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$blood = strtoupper(trim($_POST['blood']));
$password = trim($_POST['password']);

// check for duplicate email
$exists = false;
if (file_exists("donors.txt")) {
    $file = fopen("donors.txt", "r");
    while (($line = fgets($file)) !== false) {
        $parts = explode(",", $line);
        if (count($parts) >= 2 && trim($parts[1]) == $email) {
            $exists = true;
            break;
        }
    }
    fclose($file);
}

if ($exists) {
    echo "<h3>Email already registered.</h3><a href='register.html'>Try Again</a>";
    exit;
}

// append new donor (mask password for demo)
$file = fopen("donors.txt", "a");
fwrite($file, "$name,$email,$blood,***\n");
fclose($file);

echo "<h3>Registration successful!</h3>";
echo "<a href='index.html'>Back to Home</a>";
?>
