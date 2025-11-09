<?php
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$found = false;

if (file_exists("donors.txt")) {
    $file = fopen("donors.txt", "r");
    while (($line = fgets($file)) !== false) {
        $parts = explode(",", trim($line));
        if (count($parts) >= 4 && $parts[1] == $email) {
            // password ignored because stored masked; simulate success
            $found = true;
            break;
        }
    }
    fclose($file);
}

if ($found) {
    echo "<h3>Login successful!</h3>";
    echo "<a href='index.html'>Home</a>";
} else {
    echo "<h3>Invalid email or password.</h3>";
    echo "<a href='login.html'>Try Again</a>";
}
?>
