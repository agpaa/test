<?php
$to = "agpaasoftwares@gmail.com";
$subject = "Test Mail from Plesk";
$message = "This is a test email.";
$headers = "From: no-reply@example.com";
mail($to, $subject, $message, $headers);
echo "Test email sent.";
?>
