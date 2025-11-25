<?php
// Collect inputs safely
$donor      = trim($_POST['donor_name'] ?? '');
$email      = trim($_POST['donor_email'] ?? '');
$blood      = strtoupper(trim($_POST['blood'] ?? ''));
$recipient  = trim($_POST['recipient'] ?? '');
$location   = trim($_POST['location'] ?? '');
$date       = trim($_POST['date'] ?? '');

// Basic validation
if ($donor=='' || $email=='' || $blood=='' || $recipient=='' || $location=='' || $date=='') {
    echo "<h3>All fields are required.</h3><a href='history.html'>Back</a>";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<h3>Invalid email format.</h3><a href='history.html'>Back</a>";
    exit;
}

// Append donation entry to history.txt
$f = fopen('history.txt', 'a');
fputcsv($f, [$donor, $email, $blood, $recipient, $location, $date]);
fclose($f);

// Update donor's last donation date in donors.txt
$sourceFile = 'donors.txt';
$tempFile = sys_get_temp_dir() . '/donor_update_' . time() . '.txt';

$fr = fopen($sourceFile, 'r');
$fw = fopen($tempFile, 'w');

while (($line = fgets($fr)) !== false) {
    $line = trim($line);
    if ($line === '') continue;

    $cols = str_getcsv($line);

    // Ensure donor record always has 11 fields (your new format)
    for ($i = 0; $i < 11; $i++) {
        if (!isset($cols[$i])) {
            $cols[$i] = "none";
        }
    }

    // If this record matches donor email → update last donation date (index 8)
    if (strcasecmp(trim($cols[1]), $email) === 0) {
        $cols[8] = $date;
    }

    fputcsv($fw, $cols);
}

fclose($fr);
fclose($fw);

// Replace original file with updated version
rename($tempFile, $sourceFile);

echo "<h3 style='color:green; text-align:center;'>Donation recorded successfully.</h3>";
echo "<div style='text-align:center; margin-top:18px;'>
        <a href='viewhistory.php'>View Donation History</a>
      </div>";
?>
