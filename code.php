<?php
ob_start();
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Function to send verification email
function sendemail_verify($name, $email, $verify_token)
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "your-email@gmail.com"; // Replace with your email
    $mail->Password = "your-app-password"; // Use an App Password, not your Gmail password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('no-reply@feur-cape.com', 'FEU ROOSEVELT CAPE');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Email Verification from FEU Roosevelt CAPE";

    $email_template = "
    <h2>Welcome to FEU Roosevelt CAPE</h2>
    <h5>Click the link below to verify your email:</h5>
    <a href='http://localhost/feur-cape/verify-email.php?token=$verify_token'>Verify Email</a>";

    $mail->Body = $email_template;
    
    try {
        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }
}

// **User Registration**
if (isset($_POST['register_btn'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $verify_token = md5(rand());

    if ($password !== $confirm_password) {
        $_SESSION['status'] = "Passwords do not match!";
        header("Location: register.php");
        exit();
    }

    // Hash password securely
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $stmt = $con->prepare("SELECT email FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['status'] = "Email already exists";
        header("Location: register.php");
        exit();
    } else {
        // Insert user into database
        $stmt = $con->prepare("INSERT INTO users (name, email, password, verify_token, role, is_verified) VALUES (?, ?, ?, ?, 'user', 0)");
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $verify_token);
        
        if ($stmt->execute()) {
            sendemail_verify($name, $email, $verify_token);
            $_SESSION['status'] = "Registration Successful! Check your email for verification.";
            header("Location: register.php");
        } else {
            $_SESSION['status'] = "Registration Failed";
            header("Location: register.php");
        }
        exit();
    }
}

// **User Login**
if (isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT id, name, email, password, role, is_verified FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (!password_verify($password, $user['password'])) {
            $_SESSION['status'] = "Invalid Email or Password!";
            header("Location: login.php");
            exit();
        }
    
        if ($user['is_verified'] == 0 && $user['role'] !== 'admin') {
            $_SESSION['status'] = "Please verify your email before logging in.";
            header("Location: login.php");
            exit();
        }
    
        // Store session data
        $_SESSION['auth'] = true;
        $_SESSION['auth_user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
    
        if ($user['role'] === 'admin') {
            header("Location: admindashboard.php");
        } else {
            header("Location: userdashboard.php");
        }
        exit();
        
    } else {
        $_SESSION['status'] = "Invalid Email or Password!";
        header("Location: login.php");
        exit();
    }
}
?>
