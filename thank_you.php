<?php
// thank_you.php - simple thank-you note (could be called after mark_completed)
$donor = trim($_GET['donor'] ?? '');
echo "<h2 style='text-align:center;color:#8B0000;'>Thank you</h2>";
if ($donor) echo "<p style='text-align:center;'>Thanks, ".htmlspecialchars($donor).", for donating. Your contribution saved lives.</p>";
else echo "<p style='text-align:center;'>Thanks for donating.</p>";
echo "<div style='text-align:center;margin-top:18px;'><a href='index.html'>Home</a></div>";
