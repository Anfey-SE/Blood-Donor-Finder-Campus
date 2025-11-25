<?php
// migrate old 4-field donors to new 11-field format
// safe: creates backup and writes upgraded file

$src = 'donors.txt';
if (!file_exists($src)) {
    echo "donors.txt not found.";
    exit;
}

$backup = 'donors_backup_' . date('Ymd_His') . '.txt';
copy($src, $backup);

$lines = file($src, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$out = [];

foreach ($lines as $line) {
    $cols = str_getcsv($line);
    if (count($cols) == 4) {
        list($name, $email, $blood, $pass) = $cols;
        $new = [
            $name,
            $email,
            'none',   // department
            $blood,
            $pass,    // keep password as-is (old) - later rehash on login/update
            '0',      // verified
            '1',      // available
            'none',   // hostel
            'none',   // lastDonation
            '0',      // emailEnabled
            '0'       // rating
        ];
        $out[] = $new;
    } else {
        // try to normalize to 11 fields
        for ($i=0;$i<11;$i++) if (!isset($cols[$i])) $cols[$i] = ($i===4 ? '' : 'none');
        $out[] = $cols;
    }
}

// write back using fputcsv to ensure proper quoting
$fp = fopen($src, 'w');
foreach ($out as $r) {
    fputcsv($fp, $r);
}
fclose($fp);

echo "Migration complete. Backup saved as $backup";
?>
