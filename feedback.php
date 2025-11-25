<?php
$email = trim($_POST['email'] ?? 'anonymous');
$message = trim($_POST['message'] ?? '');
if ($message === '') { echo "<h3>Please write feedback.</h3><a href='feedback.html'>Back</a>"; exit; }

$f = fopen('feedback.txt','a');
fputcsv($f, [ 'f_'.time().rand(10,99), $email, $message, time() ]);
fclose($f);
echo "<h3 style='color:green;text-align:center;'>Thanks for your feedback.</h3><div style='text-align:center;margin-top:18px;'><a href='index.html'>Home</a></div>";
