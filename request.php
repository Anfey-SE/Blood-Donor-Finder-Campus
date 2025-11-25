<?php
// submit a blood request, store it in requests.txt and show matching donors

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: request.html');
    exit;
}

$name  = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$blood = strtoupper(trim($_POST['blood'] ?? ''));
$location = trim($_POST['location'] ?? '');
$details = trim($_POST['details'] ?? ''); // optional extra details

// validation
if ($name === '' || $email === '' || $blood === '' || $location === '') {
    echo "<h3>Please fill all required fields.</h3><a href='request.html'>Go Back</a>";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<h3>Invalid email.</h3><a href='request.html'>Go Back</a>";
    exit;
}

if (!preg_match('/^(A|B|AB|O)[+-]$/i', $blood)) {
    echo "<h3>Invalid blood group (use A+, O-, AB+, ...).</h3><a href='request.html'>Go Back</a>";
    exit;
}

// write request with id and timestamp
$reqFile = 'requests.txt';
if (!file_exists($reqFile)) touch($reqFile);

$reqId = uniqid('req_');
$timestamp = time();
$requestRow = [
    $reqId,
    $name,
    $email,
    $blood,
    $location,
    $details,
    'open',     // status
    '',         // matchedDonorId
    $timestamp
];

// append request
$fp = fopen($reqFile, 'a');
if ($fp) {
    fputcsv($fp, $requestRow);
    fclose($fp);
}

// show message and search for matching donors
echo "<h2 style='text-align:center;color:#8B0000;'>Blood Request Submitted</h2>";
echo "<p style='text-align:center;'>Searching for verified & available donors with blood group <strong>" . htmlspecialchars($blood) . "</strong>...</p>";

// search donors
$donorFile = 'donors.txt';
$matches = [];

if (file_exists($donorFile)) {
    $fr = fopen($donorFile, 'r');
    while (($line = fgets($fr)) !== false) {
        $line = trim($line);
        if ($line === '') continue;
        $cols = str_getcsv($line);
        // ensure at least 11 cols
        for ($i=0;$i<11;$i++) if (!isset($cols[$i])) $cols[$i] = ($i===4 ? '' : 'none');

        $d_name = $cols[0];
        $d_email = $cols[1];
        $d_dept = $cols[2];
        $d_blood = strtoupper($cols[3]);
        $d_pass = $cols[4];
        $d_verified = $cols[5] ?? '0';
        $d_available = $cols[6] ?? '1';
        $d_hostel = $cols[7] ?? 'none';

        if (strcasecmp($d_blood, $blood) === 0 && $d_verified === '1' && $d_available === '1') {
            $matches[] = [
                'id' => $cols[1] . '_' . substr(md5($cols[1]),0,6),
                'name' => $d_name,
                'email' => $d_email,
                'department' => $d_dept,
                'hostel' => $d_hostel
            ];
            // optionally: append notification entry for donor (in notifications.txt)
            $noteFile = 'notifications.txt';
            $noteFp = fopen($noteFile, 'a');
            if ($noteFp) {
                $note = [
                    uniqid('n_'),
                    $d_email,
                    "Blood requested: $blood at $location (requester: $name, $email)",
                    time(),
                    'unread'
                ];
                fputcsv($noteFp, $note);
                fclose($noteFp);
            }
        }
    }
    fclose($fr);
}

// output matches
if (count($matches) === 0) {
    echo "<p style='text-align:center;color:red;'>No verified & available donors found right now for $blood.</p>";
} else {
    echo "<table border='1' style='margin:20px auto;border-collapse:collapse;width:85%;text-align:left;'>";
    echo "<tr style='background:#8B0000;color:#fff;'><th>Name</th><th>Email</th><th>Department</th><th>Hostel</th></tr>";
    foreach ($matches as $m) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($m['name']) . "</td>";
        echo "<td>" . htmlspecialchars($m['email']) . "</td>";
        echo "<td>" . htmlspecialchars($m['department']) . "</td>";
        echo "<td>" . htmlspecialchars($m['hostel']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<div style='text-align:center;margin-top:20px;'><a href='index.html'>Back to Home</a></div>";
exit;
?>
