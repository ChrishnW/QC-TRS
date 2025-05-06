<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader (created by composer, not included with PHPMailer)
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.office365.com';                   //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'noreply@glory.com.ph';                 //SMTP username
    $mail->Password   = 'C0nn3t@0711';                          //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('noreply@glory.com.ph', 'Glory Philippines Inc.'); //Sender's email and name

    //Add a recipient (Recipient's email and name)
    $mail->addAddress('requestor@glory.ph', 'Requestor'); 
    $mail->addAddress('editor@glory.com', 'Editor'); 
    $mail->addAddress('head@glory.com', 'Department Head'); 
    $mail->addAddress('factory@glory.com', 'Factory Officer'); 
    $mail->addAddress('supervisor@glory.com', 'Supervisor'); 
    $mail->addAddress('coo@glory.com', 'COO'); 

    $mail->addCC('requestor@example.com');
    $mail->addCC('editor@example.com');
    $mail->addCC('head@example.com');
    $mail->addCC('factory@example.com');
    $mail->addCC('supervisor@example.com');
    $mail->addCC('coo@example.com');

    //Recipients
    // $mail->setFrom('from@example.com', 'Mailer');
    // $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
    // $mail->addAddress('ellen@example.com');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    $mail->addAttachment('/assets/img/logo.png', 'test.png');    //Add attachments, Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Quality Control Trouble Report';
    $mail->Body    = 'Attached here is the <b>Quality Control Trouble Report</b> for your reference.';
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}