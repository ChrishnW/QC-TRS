<?php
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader (created by composer, not included with PHPMailer)
    require '../vendor/autoload.php';

    function sendEmail($email_address, $name) {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.office365.com';                   //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'noreply@glory.com.ph';                 //SMTP username
            $mail->Password   = 'C0nn3ct@0711';                         //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Sender's email and name
            $mail->setFrom('noreply@glory.com.ph', 'Glory Philippines Inc.'); 

            //Recipients
            // $mail->addAddress($email_address, $name); 

            // Content
            $mail->isHTML(true);                                        
            $mail->Subject = '[REQUEST]: TROUBLE REPORT';
            $mail->Body    = 'Dear <strong> . $name . </strong>, <br><br>
                            You have PENDING Trouble Report Request [Trouble Report No.] for approval.  <br>
                            Please check by logging in your account at [link of QCTRS]                  <br><br>
                            <i>This is a system generated email. Please do not reply.</i>               <br><br>
                            QC Trouble Report System';
            $mail->send();

            echo 'Message has been sent.';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }