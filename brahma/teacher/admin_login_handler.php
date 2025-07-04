<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "udhayam_institute");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check credentials
    $sql = "SELECT * FROM admin WHERE email='$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Generate OTP
            $otp = mt_rand(100000, 999999);
            $_SESSION['admin_email'] = $email;
            $_SESSION['otp'] = $otp;

            // Store OTP in database
            $otp_expiry = date('Y-m-d H:i:s', strtotime('+10 minutes')); // OTP expiry time
            $sql = "UPDATE admin SET otp='$otp', otp_expiry='$otp_expiry' WHERE email='$email'";
            if ($conn->query($sql) === TRUE) {
                // Send OTP via email
                sendOTP($email, $otp);
                header("Location: verify_otp.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Invalid email or password";
        }
    } else {
        echo "Invalid email or password";
    }

    $conn->close();
}

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Update with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'udhayaminstitute6@gmail.com'; // Replace with your email
        $mail->Password = 'ipqx kzlt ktxz wilw'; // Replace with your email password or app-specific password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('udhayaminstitute6@gmail.com', 'UDHAYAM INSTITUTE');
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP code is: $otp";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
