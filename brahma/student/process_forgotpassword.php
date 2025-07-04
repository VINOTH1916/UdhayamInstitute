<?php
// Include the PHPMailer dependencies
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/Exception.php';

// Start a session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    
    // Generate a random OTP
    $otp = mt_rand(100000, 999999); // Generate a 6-digit OTP

    // Store the OTP in the session for verification
    $_SESSION["otp"] = $otp;

    // Prepare the email data
    $to = $email;
    $subject = "Password Reset OTP";
    $message = "Your OTP for password reset is: " . $otp;

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'udhayaminstitute6@gmail.com';
        $mail->Password = 'ipqx kzlt ktxz wilw'; // Use your email password here
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('udhayaminstitute6@gmail.com', 'UDHAYAM INSTITUTE');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Send email
        $mail->send();
        echo "OTP sent to your email. Please check your inbox.";
    } catch (Exception $e) {
        echo "Error sending email: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-top: 50px;
        }

        form {
            width: 300px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Verify OTP</h1>
    <form action="verify_otp.php" method="POST">
        <label for="otp">Enter OTP:</label>
        <input type="text" id="otp" name="otp" required>
        <input type="submit" value="Verify OTP">
    </form>
</body>
</html>

