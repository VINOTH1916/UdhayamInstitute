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

    // Fetch admin credentials from the database
    $sql = "SELECT password FROM admin WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Validate email and password
        if (password_verify($password, $hashed_password)) {
            // Generate OTP
            $otp = mt_rand(100000, 999999);

            $_SESSION['admin_email'] = $email;
            $_SESSION['otp'] = $otp;

            // Store OTP in database
            $otp_expiry = date('Y-m-d H:i:s', strtotime('+10 minutes')); // OTP expiry time (adjust as needed)

            $sql_update = "UPDATE admin SET otp='$otp', otp_expiry='$otp_expiry' WHERE email='$email'";
            if ($conn->query($sql_update) === TRUE) {
                // Send OTP via email
                sendOTP($email, $otp);
                header("Location: verify_otp.php");
                exit();
            } else {
                echo "Error: " . $sql_update . "<br>" . $conn->error;
            }
        } else {
            echo "Invalid email or password";
        }
    } else {
        echo "Invalid email or password";
    }
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
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {
  font-family: Arial, Helvetica, sans-serif;
}

* {
  box-sizing: border-box;
}

.preloader {
    position: absolute;
    z-index: 999;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    align-items: center;
    justify-content: center;
    display: none;
}

.preloader video {
    max-width: 100%;
    max-height: 100%;
}

.button {
  background-color: black;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}

.container {
  position: relative;
  border-radius: 5px;
  background-color: lightgrey;
  padding: 80px 0 100px 0;
} 

input,
.btn {
  width: 100%;
  padding: 12px;
  border: none;
  border-radius: 4px;
  margin: 5px 0;
  opacity: 0.85;
  display: inline-block;
  font-size: 17px;
  line-height: 20px;
  text-decoration: none; 
}

input:hover,
.btn:hover {
  opacity: 1;
}

.fb {
  background-color: #3B5998;
  color: white;
}

.twitter {
  background-color: #55ACEE;
  color: white;
}

.google {
  background-color: #dd4b39;
  color: white;
}

input[type=submit] {
  background-color: #04AA6D;
  color: white;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

.col {
  float: left;
  width: 50%;
  margin: auto;
  padding: 0 50px;
  margin-top: 6px;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

.vl {
  position: absolute;
  left: 50%;
  transform: translate(-50%);
  border: 2px solid #ddd;
  height: 175px;
}

.vl-innertext {
  position: absolute;
  top: 50%;
  transform: translate(-50%, -50%);
  background-color: #f1f1f1;
  border: 1px solid #ccc;
  border-radius: 50%;
  padding: 8px 10px;
}

.hide-md-lg {
  display: none;
}

.bottom-container {
  text-align: center;
  background-color: #666;
  border-radius: 0px 0px 4px 4px;
}

@media screen and (max-width: 650px) {
  .col {
    width: 100%;
    margin-top: 0;
  }
  
  .vl {
    display: none;
  }
 
  .hide-md-lg {
    display: block;
    text-align: center;
  }
}
</style>
</head>

<body>
    <h2>Admin Login</h2>
    <div class="container">

    <div id="preloader" class="preloader">
        <video autoplay loop muted height="200px" width="auto">
            <source src="loading-video.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <form id="admin-form" method="POST" action="admin_login_handler.php">
        <div class="row">
            <center><img src="C:/Users/91979/Desktop/uhayam/WhatsApp Image 2023-09-21 at 9.36.48 PM.jpeg" width=10%></center>
            <h2 style="text-align:center">UDHAYAM INSTITUTE</h2>
            <div class="vl">
                <span class="vl-innertext">or</span>
            </div>

            <div class="col">
                <a href="https://www.facebook.com/login.php" class="btn fb">
                    <i class="fa fa-facebook fa-fw"></i> Login with Facebook
                </a>
                <a href="https://twitter.com/i/flow/login" class="btn twitter">
                    <i class="fa fa-twitter fa-fw"></i> Login with Twitter
                </a>
                <a href="#" class="btn google">
                    <i class="fa fa-phone fa-fw"></i> Login with phone number
                </a>
                <a href="1.html" class="btn">
                    <i class="fa fa-home fa-fw"></i> back to home
                </a>
            </div>

            <div class="col">
                <div class="hide-md-lg">
                </div>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button class="button" type="submit">LOGIN</button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('admin-form');
    const preloader = document.getElementById('preloader');

    form.addEventListener('submit', function (e) {
        // Prevent the form from submitting multiple times
        if (form.getAttribute('data-submitting') === 'true') {
            e.preventDefault();
            return;
        }

        // Disable the submit button and show the preloader
        form.setAttribute('data-submitting', 'true');
        preloader.style.display = 'flex';
        form.querySelector('button[type="submit"]').disabled = true;
    });
});
</script>

</body>
</html>
