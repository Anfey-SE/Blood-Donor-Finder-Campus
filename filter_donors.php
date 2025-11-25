<?php
// filter donors by department and/or hostel (shows only verified & available)

$department = trim($_POST['department'] ?? '');
$hostel = trim($_POST['hostel'] ?? '');

if ($department === '' && $hostel === '') {
    echo "<h3>Enter department or hostel to filter.</h3><a href='filter_donors.html'>Back</a>";
    exit;
}

$donorFile = 'donors.txt';
$found = false;

echo "<h2 style='text-align:center;color:#8B0000;'>Filtered Donors</h2>";
echo "<table border='1' style='margin:auto;border-collapse:collapse;width:85%;text-align:left;'>";
echo "<tr style='background:#8B0000;color:#fff;'><th>Name</th><th>Email</th><th>Blood</th><th>Department</th><th>Hostel</th></tr>";

if (file_exists($donorFile)) {
    $fr = fopen($donorFile, 'r');
    while (($line = fgets($fr)) !== false) {
        $line = trim($line);
        if ($line === '') continue;
        $cols = str_getcsv($line);
        for ($i=0;$i<11;$i++) if (!isset($cols[$i])) $cols[$i] = ($i===4 ? '' : 'none');

        $d_name = $cols[0];
        $d_email = $cols[1];
        $d_dept = $cols[2];
        $d_blood = $cols[3];
        $d_verified = $cols[5] ?? '0';
        $d_available = $cols[6] ?? '1';
        $d_hostel = $cols[7] ?? 'none';

        if ($d_verified !== '1' || $d_available !== '1') continue;

        $matchDept = $department === '' || stripos($d_dept, $department) !== false;
        $matchHostel = $hostel === '' || stripos($d_hostel, $hostel) !== false;

        if ($matchDept && $matchHostel) {
            $found = true;
            echo "<tr>";
            echo "<td>" . htmlspecialchars($d_name) . "</td>";
            echo "<td>" . htmlspecialchars($d_email) . "</td>";
            echo "<td>" . htmlspecialchars($d_blood) . "</td>";
            echo "<td>" . htmlspecialchars($d_dept) . "</td>";
            echo "<td>" . htmlspecialchars($d_hostel) . "</td>";
            echo "</tr>";
        }
    }
    fclose($fr);
}

echo "</table>";

if (!$found) {
    echo "<p style='text-align:center;color:red;'>No donors found for the selected filters.</p>";
}

echo "<div style='text-align:center;margin-top:20px;'><a href='filter_donors.html'>Back</a> | <a href='index.html'>Home</a></div>";
exit;
?>
