<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function resend_email_verify($name, $email, $verify_token)
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();

    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "mirasol0522@gmail.com";
    $mail->Password = "hctu znoi cjou enrh";

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('feurooseveltcape@gmail.com', 'FEU ROOSEVELT CAPE');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Email Verification from FEU Roosevelt CAPE";

    $email_template = "
    <h2>You have requested for an e-mail to be re-sent</h2>
    <h2>You have Registered with FEU Roosevelt CAPE</h2>
    <h5>Verify your Email Address to login with the below link</h5>
    <br/><br/>
    <a href = 'http://localhost/feur-cape/verify-email.php?token=$verify_token'> Click Me </a>
    ";

    $mail->Body = $email_template;
    if(!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } 
    else {
        echo 'Message sent!';
    }
}
{

}

if(isset($_POST['resend_email_verify_btn']))
{
    if(!empty(trim($_POST['email'])))
    {
        $email = mysqli_real_escape_string($con, $_POST['email']);

        $checkemail_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $checkemail_query_run = mysqli_query($con, $checkemail_query);

        if(mysqli_num_rows($checkemail_query_run) > 0)
        {
            $row = mysqli_fetch_array($checkemail_query_run);
            if($row['verify_status'] == "0")
            {
                $name = $row['name'];
                $email = $row['email'];
                $verify_token = $row['verify_token'];

                resend_email_verify($name, $email, $verify_token);

                $_SESSION['status'] = "Your Verification Email has been re-sent. Please check your inbox";
                header("Location: login.php");
                exit(0);
            }
            else
            {
                $_SESSION['status'] = "Email already verified. Please Login";
                header("Location: login.php");
                exit(0);
            }
        }
        else
        {
            $_SESSION['status'] = "Email is not registered. Please Register first.";
            header("Location: register.php");
            exit(0);
        }
    }
    else
    {
        $_SESSION['status'] = "Please enter your email.";
        header("Location: resend-email-verification.php");
        exit(0);
    }
}

?>