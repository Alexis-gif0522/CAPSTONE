<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendemail_verify($name, $email, $verify_token)
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

if(isset($_POST['register_btn']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verify_token = md5(rand());

    // Email exists or not
    $check_email_query = "SELECT email FROM users WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if(mysqli_num_rows($check_email_query_run) > 0)
    {
        $_SESSION['status'] = "Email ID already exists";
        header("Location: register.php");
    }
    else
    {
        // Insert user / Registered user data
        $query = "INSERT INTO users (name,email,password,verify_token) VALUES ('$name','$email','$password','$verify_token')";
        $query_run = mysqli_query($con, $query);

        if($query_run)
        {
            sendemail_verify("$name", "$email", "$verify_token");

            $_SESSION['status'] = "Registration Successful! Please verify your Email Address";
            header("Location: register.php");
        }
        else
        {
            $_SESSION['status'] = "Registration Failed";
            header("Location: register.php");
        }
    }
}

?>