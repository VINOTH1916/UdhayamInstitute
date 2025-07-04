<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in admin table
    $conn = new mysqli("localhost", "root", "", "udhayam_institute");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM admin WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Generate OTP
        $otp = mt_rand(100000, 999999);
        $_SESSION['forgot_email'] = $email;
        $_SESSION['forgot_otp'] = $otp;

        // Store OTP in database
        $otp_expiry = date('Y-m-d H:i:s', strtotime('+10 minutes')); // OTP expiry time (adjust as needed)

        $sql_update = "UPDATE admin SET otp='$otp', otp_expiry='$otp_expiry' WHERE email='$email'";
        if ($conn->query($sql_update) === TRUE) {
            // Send OTP via email
            sendOTP($email, $otp);
            header("Location: reset_password.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Email not found. Please try again.";
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
        $mail->addAddress($email); // Add recipient

        // Content
        $mail->isHTML(false); // Set email format to HTML
        $mail->Subject = 'OTP for Admin Login';
        $mail->Body = "Your OTP for Admin Login is: $otp";

        $mail->send();
        echo 'An OTP has been sent to your email. Please check your inbox.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>

    <style>
body {
  font-family: Arial, Helvetica, sans-serif;
  background-color: black;
}
.button {
  padding: 15px 25px;
  font-size: 24px;
  text-align: center;
  cursor: pointer;
  outline: none;
  color: #fff;
  background-color: black;
  border: none;
  border-radius: 15px;
  box-shadow: 0 9px #999;
}

.button:hover {background-color: #3e8e41}

.button:active {
  background-color: #3e8e41;
  box-shadow: 0 5px #666;
  transform: translateY(4px);
}

* {
  box-sizing: border-box;
}


.container {
  padding: 16px;
  background-color: white;
}


input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}


hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}


.registerbtn {
  background-color: #04AA6D;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}

a {
  color: dodgerblue;
}


.signin {
  background-color: #f1f1f1;
  text-align: center;
}
</style>

</head>
<body>
    <h2>Forgot Password</h2>
    <div class="container">
        <h2>Forgot Password</h2>
        <form method="post">
            <label>Enter your registered email:</label>
            <input type="email" name="email" required><br><br>
            <input class="registerbtn" type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
