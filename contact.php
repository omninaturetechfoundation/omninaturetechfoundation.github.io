<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
	$phone = $_POST["phone"];
    $message = $_POST["message"];
	
    $recaptchaSecretKey = '6LdHLsYnAAAAAAGLX_NPpXeqwzbhkipDEsYWd3Ti';
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecretKey&response=$recaptchaResponse";
    $recaptchaResult = json_decode(file_get_contents($recaptchaUrl), true);
	
    // Validate input
    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        echo "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
    } else {
        
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // SMTP server details
        $mail->SMTPAuth = true;
        $mail->Username = 'your@example.com'; // SMTP username
        $mail->Password = 'your-smtp-password'; // SMTP password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        
        $mail->setFrom($email, $name);
        $mail->addAddress('omninaturetechfoundation@gmail.com'); // Recipient's email
        
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body = $message;
		
    if (!$recaptchaResult['success']) {
        echo "reCAPTCHA verification failed.";
    } else {
        if ($mail->send()) {
            echo "Thank you for contacting us, $name!";
        } else {
            echo "Oops! Something went wrong.";
        }
	  }
    }	
}
?>
