<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/Exception.php';

// Function to generate a password based on the teacher's name and a random string
function generatePassword($teacher_name) {
    $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
    return $randomString;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
    $date_of_join = $_POST["date_of_join"];

    // Generate a random password for the teacher
    $password = generatePassword($name);

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $db_password = "";
    $dbname = "udhayam_institute";

    // Create a connection
    $conn = new mysqli($servername, $username, $db_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email already exists
    $checkEmailSql = "SELECT * FROM teachers WHERE email = '$email'";
    $result = $conn->query($checkEmailSql);

    if ($result->num_rows > 0) {
        echo '<div style="text-align: center; margin-bottom: 20px;">
                  <div style="background-color: white; color: red; padding: 10px; border:1px solid red; margin-bottom: 10px;">Error: Student already exists with this name and email.</div>
                  </div>';
    } else {
        // Insert teacher into the database
        $sql = "INSERT INTO teachers (name, email, password, phone_number, date_of_join) VALUES ('$name', '$email', '$password', '$phone_number', '$date_of_join')";

        if ($conn->query($sql) === TRUE) {
            // Teacher added successfully
            echo '<div style="text-align: center; margin-bottom: 20px;">
            <div style="background-color: white; color: green; padding: 10px; border:1px solid red; margin-bottom: 10px;">Teacher added successfully!</div>
            </div>';
          

            // Send email to the teacher with login details
            $subject = "Welcome to UDHAYAM INSTITUTE";
            $message = "Dear $name,\n\n";
            $message .= "Welcome to UDHAYAM INSTITUTE! Your login credentials are:\n";
            $message .= "Email: $email\n";
            $message .= "Password: $password\n\n";
            $message .= "Please keep this information secure.\n\n";
            $message .= "Best regards,\n";
            $message .= "UDHAYAM INSTITUTE Team";

            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'udhayaminstitute6@gmail.com'; // UDHAYAM INSTITUTE email address
                $mail->Password = 'ipqx kzlt ktxz wilw'; // Replace with the actual password or app-specific password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Sender and recipient
                $mail->setFrom('udhayaminstitute6@gmail.com', 'UDHAYAM INSTITUTE');
                $mail->addAddress($email, $name);

                // Email content
                $mail->isHTML(false); // Set to true if using HTML content
                $mail->Subject = $subject;
                $mail->Body = $message;

                // Send email
                $mail->send();
              echo  '<div style="text-align: center; margin-bottom: 20px;">
            <div style="background-color: white; color: green; padding: 10px; border:1px solid red; margin-bottom: 10px;">Email sent successfully!</div>
            </div>';
          
                
            } catch (Exception $e) {
                echo "Error sending email: " . $e->getMessage();
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
}
?>




<!DOCTYPE html>
<html>
<head>
    <title>Add Teacher</title>
    <link rel="stylesheet" type="text/css" href="formcss.css">
    <style>
        .container {
            width: 50%;
            margin: 0 auto;
            height: 530px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        
        
    </style>
</head>
<body>

<div id="preloader" class="preloader">
    <video autoplay loop muted height="200px" width="auto">
        <source src="loading-video.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>

<div class="container">
<button onclick="window.location.href='admin.php'" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 10px 0;">Home</button>
    <h1>Add Teacher</h1>

    
    <form id="teacher-form" action="add_teacher.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" required>
        <label for="date_of_join">Date of Joining:</label>
        <input type="date" id="date_of_join" name="date_of_join" required>
        <input type="submit" value="Add Teacher" class="submit-button">
    </form>
   

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const preloader = document.getElementById('preloader');
        preloader.style.display = 'none';

        const form = document.getElementById('teacher-form');

        form.addEventListener('submit', function (e) {
            // Prevent the form from submitting multiple times
            if (form.getAttribute('data-submitting') === 'true') {
                e.preventDefault();
                return;
            }

            // Disable the submit button and show the preloader
            form.setAttribute('data-submitting', 'true');
            preloader.style.display = 'block';
            form.querySelector('input[type="submit"]').disabled = true;
        });
    });
</script>

</body>
</html>
