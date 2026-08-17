<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
//echo "$root/api.php";
include "$root/api.php";
//echo $root . '/api.php';
$errors = [];
$errorMessage = '';

$secretKey = '6LeGdPUqAAAAAACTAgKDMzVGtsjSeihncEyX81y7'; // This is gogle reCaptcha Secret key.

if (!empty($_POST)) {
	$recaptchaResponse = $_POST['g-recaptcha-response'];
	$recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents($recaptchaUrl . '?secret=' . $secretKey . '&response=' . $recaptchaResponse);
    $responseKeys = json_decode($response, true);

	if(intval($responseKeys['success']) !== 1) {
        echo 'reCAPTCHA verification failed. Please try again.';
    } else
	{
    $fname = $_POST['fname'];
	$lname = $_POST['lname'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];


    if (empty($fname)) {
        $errors[] = 'First Name is empty';
    }
	if (empty($lname)) {
        $errors[] = 'Last Name is empty';
    }

    if (empty($email)) {
        $errors[] = 'Email is empty';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is invalid';
    }
   if (empty($subject)) {
        $errors[] = 'Subject is empty';
    }

    if (empty($message)) {
        $errors[] = 'Message is empty';
    }


    if (!empty($errors)) {
        $allErrors = join('<br/>', $errors);
        $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
		echo $errorMessage;
    } else {
		$post_data = [
			"properties" => [
             "email" => $email,
             "firstname" => $fname,
             "lastname" => $lname,
			 "subject" => $subject,
             "message" => $message
				]
         ];

		//echo makeApiCall($post_data);
		if (makeApiCall($post_data)) {
        echo 'OK';
    } else {
       echo "Sorry, there was an error sending your message. Please try again later.";
   }
    }
	}
}

?>
