<?php
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader (created by composer, not included with PHPMailer)
    require '../vendor/autoload.php';

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

        $id = $_SESSION['request_id'];
        $access = $_SESSION['access'];
        $query = "";

        if($access == 3){
            $query = "tbl_request.dept_id";
        }
        elseif($access == 4){
            $query = "tbl_request.dept_head_id";
        }
        elseif($access == 5){
            $query = "tbl_request.fac_officer_id";
        }
        elseif($access == 6){
            $query = "tbl_request.supervisor_id";
        }
        elseif($access == 6){
            $query = "tbl_request.coo_id";
        }



        $result = mysqli_query($conn, "SELECT tbl_account.email, tbl_account.firstname, tbl_account.lastname, tbl_request.date FROM tbl_request INNER JOIN tbl_account ON $query=tbl_account.id WHERE tbl_request.id=$id");
        $details = mysqli_fetch_assoc($result);

        $email = $details['email'];
        $name = $details['firstname'] . " " . $details['lastname'];
        $date = explode('-' , $details['date']);
        $tr_number = "QTRS-" . $date[1] . $date[2] . $date[0];

        //Recipients
        $mail->addAddress($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'REQUEST: TROUBLE REPORT';
        $mail->Body    = 'Dear <strong>' . $name . '</strong>,<br><br>
                        You have a PENDING Trouble Report Request <strong>' . $tr_number . '</strong> for approval.<br>
                        Please check by logging in to your account at <a href="http://localhost/qc-trs" target="_blank">Trouble Report System</a>.<br><br>
                        <i>This is a system-generated email. Please do not reply.</i><br><br>
                        QC Trouble Report System';
        $mail->send();


        echo "<script>console.log('Message has been sent')</script>";
    } catch (Exception $e) {
        echo "<script>console.log(Message could not be sent. Mailer Error: {$mail->ErrorInfo})</script>";
    }
?>