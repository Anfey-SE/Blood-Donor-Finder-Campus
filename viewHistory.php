<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Donation History | Blood Donor Finder</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    h2 {
        text-align:center;
        color:#8B0000;
        margin-top:25px;
    }
    .history-table {
        width:90%;
        margin:30px auto;
        border-collapse:collapse;
        box-shadow:0 4px 12px rgba(0,0,0,0.1);
        background:white;
    }
    .history-table th {
        background:#8B0000;
        color:white;
        padding:12px;
        font-size:16px;
    }
    .history-table td {
        padding:10px;
        border-bottom:1px solid #ddd;
        text-align:center;
    }
    .history-table tr:hover {
        background:#ffe6e6;
    }
    .back-home {
        text-align:center;
        margin:20px 0 40px;
    }
    .back-home a {
        background:#8B0000;
        color:white;
        padding:10px 16px;
        border-radius:6px;
        text-decoration:none;
        font-weight:600;
    }
    .back-home a:hover {
        background:#5a0000;
    }
</style>
</head>

<body>

<!-- 🔥 NEW CLEAN NAVBAR (Same as search.php, recent requests, etc.) -->
<nav class="navbar">
  <div class="logo"><i class="fa-solid fa-droplet"></i> Blood Donor Finder</div>

  <div class="nav-links">
    <a href="index.html">Home</a>
    <a href="register.html">Register</a>
    <a href="login.html">Login</a>
    <a href="search.html">Search</a>
    <a href="request.html">Request</a>
    <a href="profile.html">Profile</a>
    <a href="reset.html">Reset</a>
    <a href="availability.html">Availability</a>
    <a href="admin.html">Admin</a>
    <a href="viewhistory.php" style="font-weight:bold; color:#ffcccc;">History</a>
  </div>
</nav>

<?php
echo "<h2>Donation History</h2>";

$historyFile = "history.txt";

if (!file_exists($historyFile)) {
    echo "<p style='text-align:center;'>No donation records yet.</p>";
    echo "<div class='back-home'><a href='index.html'>Back to Home</a></div>";
    exit;
}

$fh = fopen($historyFile, "r");

echo "<table class='history-table'>";
echo "<tr>
        <th>Donor</th>
        <th>Email</th>
        <th>Blood Group</th>
        <th>Recipient</th>
        <th>Location</th>
        <th>Date</th>
      </tr>";

while (($line = fgets($fh)) !== false) {
    $line = trim($line);
    if ($line === "") continue;

    $cols = str_getcsv($line);

    if (count($cols) < 6) continue;

    echo "<tr>";
    foreach ($cols as $c) {
        echo "<td>" . htmlspecialchars($c) . "</td>";
    }
    echo "</tr>";
}

echo "</table>";
fclose($fh);
?>

<div class="back-home">
    <a href="index.html">Back to Home</a>
</div>

</body>
</html>
