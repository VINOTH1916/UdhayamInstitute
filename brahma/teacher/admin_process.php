<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/Exception.php';

// Function to generate a password based on the student's name, date of birth, and standardfunction generatePassword($student_name, $date_of_birth, $std) {
    function generatePassword($student_name, $date_of_birth, $std) {
        $first4Chars = substr($student_name, 0, 4);
        $stdPrefix = substr($std, 0, 2); // Take the first two characters of std
        $dob = date('d', strtotime($date_of_birth));
    
        // Pad dob to ensure it's two characters
        $dob = str_pad($dob, 2, '0', STR_PAD_LEFT);
    
        return $first4Chars . $stdPrefix . $dob;
    }
    
    // Function to generate the roll number based on the form data
    function generateRollNumber($std, $name_of_grp, $conn) {
        // Calculate the current year
        $currentYear = date("Y");
    
        // Determine the class-specific prefix
        $classPrefix = '';
        if ($std == "10") {
            $classPrefix = "00"; // 10th class
        } elseif ($std == "11" && $name_of_grp == "art") {
            $classPrefix = "05"; // 11th Arts
        } elseif ($std == "11" && $name_of_grp == "sci") {
            $classPrefix = "15"; // 11th Science
        } elseif ($std == "12" && $name_of_grp == "art") {
            $classPrefix = "10"; // 12th Arts
        } elseif ($std == "12" && $name_of_grp == "sci") {
            $classPrefix = "20"; // 12th Science
        }
    
        // Query the database to count the number of students in this class
        $countSql = "SELECT COUNT(*) as student_count FROM students WHERE std = '$std' AND name_of_grp = '$name_of_grp'";
        $result = $conn->query($countSql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $studentCount = $row['student_count'] + 1;
        } else {
            $studentCount = 1; // Default to 1 if no students found
        }
    
        // Generate the last two digits based on the student count
        $studentCountStr = str_pad($studentCount, 2, '0', STR_PAD_LEFT);
    
        // Combine the components to create the roll number
        $roll_no = $currentYear . $std . $classPrefix . $studentCountStr;
    
        return $roll_no;
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve data from the form
        $student_name = $_POST["student_name"];
        $std = $_POST["std"];
        $email = $_POST["email"];
        $ph_no = $_POST["ph_no"];
        $name_of_grp = $_POST["name_of_grp"];
    
        // Perform database connection and INSERT query here
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
    
        // Check for duplicate student based on student_name and email
        $checkSql = "SELECT * FROM students WHERE student_name = '$student_name' AND email = '$email'";
        $checkResult = $conn->query($checkSql);
    
        if ($checkResult->num_rows > 0) {
            echo '
                <div style="text-align: center; margin-bottom: 20px;">
                  <div style="background-color: white; color: red; padding: 10px; border:1px solid red; margin-bottom: 10px;">Error: Student already exists with this name and email.</div>
                  </div>';
     }  else {
            $date_of_birth = date('Y-m-d'); // Placeholder date of birth
            $password = generatePassword($student_name, $date_of_birth, $std);
    
            // SQL INSERT statement for basic info
            $sql = "INSERT INTO students (student_name, std, email, ph_no, name_of_grp, roll_no, password) VALUES ('$student_name', '$std', '$email', '$ph_no', '$name_of_grp', '', '$password')";
    
            if ($conn->query($sql) === TRUE) {
                $newStudentId = $conn->insert_id; // Get the unique ID of the new student
                
                // Generate roll number
                $roll_no = generateRollNumber($std, $name_of_grp, $conn);
    
                // Update the database to set the roll_no for the newly inserted student
                $updateRollNoSql = "UPDATE students SET roll_no = '$roll_no' WHERE student_id = $newStudentId";
                $conn->query($updateRollNoSql);
    
                // Generate login token
                $token = bin2hex(random_bytes(16));
    
                // Save token to database with expiry date
                $expiryDate = date("Y-m-d H:i:s", strtotime('+1 day'));
                $tokenSql = "INSERT INTO student_tokens (student_id, token, expiry_date) VALUES ('$newStudentId', '$token', '$expiryDate')";
                $conn->query($tokenSql);
    
                // Prepare the email data
                $to = $email;
                $subject = "Complete Your Registration at UDHAYAM INSTITUTE";
                $loginLink = "http:udhayaminstitute.co.in/teacher/student_login.php?token=$token";
                $message = "Dear $student_name,\n\n";
                $message .= "Welcome to UDHAYAM INSTITUTE! Please complete your registration by following the link below:\n\n";
                $message .= "$loginLink\n\n";
                $message .= "Your roll number is: $roll_no\n";
                $message .= "Your password is: $password\n\n";
                $message .= "Best regards,\n";
                $message .= "UDHAYAM INSTITUTE\n";
                $message .= "Contact Us: +91 9384650430 | udhayaminstitutekundrathur@gmail.com\n";
                $message .= "Visit Us: www.udhayaminstitute.co.in\n";
    
                // Create a new PHPMailer instance
                $mail = new PHPMailer(true);
    
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'udhayaminstitute6@gmail.com';
                    $mail->Password = 'ipqx kzlt ktxz wilw';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
    
                    // Sender and recipient
                    $mail->setFrom('udhayaminstitute6@gmail.com', 'UDHAYAM INSTITUTE');
                    $mail->addAddress($to);
    
                    // Email content
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body = nl2br($message);
    
                    // Send email
                    $mail->send();
                    $successMessage = "Record inserted successfully, and an email has been sent.<br>";
                    $successMessage .= "Student Name: " . $student_name . "<br>";
                    $successMessage .= "Roll Number: " . $roll_no;
                    echo "Email sent successfully!";
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
    <title>Insert Student Basic Data</title>
    <link rel="stylesheet" type="text/css" href="formcss.css">
</head>
<style>  


body
{
    background-color: black;
}

h1{
    color:white;
}

  .container {
    max-width: 400px;
    height: 650px;
    margin: 0 auto;
    padding: 30px;
    background-color: #f0f0f0;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s;
    position: relative;
    font-size: 20px;
}

</style>

<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <?php
        if (isset($successMessage)) {
            echo '<div style="background-color: white; color: green; padding: 10px; border:1px solid red; margin-bottom: 10px;">' . $successMessage . '</div>';
        }
        ?>
    </div>

    <h1>Insert Student Basic Data</h1>
    <div class="container">
    <button onclick="window.location.href='admin.php'" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 10px 0;">Home</button>

    <div id="preloader" class="preloader">
            <video autoplay loop muted height="200px" width="auto">
                <source src="loading-video.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>

        <form id="student-form" action="admin_process.php" method="POST">
            <h2>UDHAYAM INSTITUTE</h2>
            <label for="student_name">Student Name:</label>
            <input type="text" id="student_name" name="student_name" required>

            <label for="std">Standard:</label>
            <select id="std" name="std" required>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="ph_no">Phone Number:</label>
            <input type="tel" id="ph_no" name="ph_no" pattern="[0-9]{10}" placeholder="9934567890" required>

            <label for="name_of_grp">Name of Group:</label>
            <select id="name_of_grp" name="name_of_grp" required>
                <option value="none">None</option>
                <option value="art">Art</option>
                <option value="sci">Science</option>
            </select>

            <input type="submit" value="Insert">
        </form>
    </div>
    <button onclick="window.location.href='admin.php'" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 10px 0;">Home</button>
</body>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('student-form');
    const preloader = document.getElementById('preloader');

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

    // Initialize group selection based on standard
    const stdSelect = document.getElementById('std');
    const groupSelect = document.getElementById('name_of_grp');

    stdSelect.addEventListener('change', function () {
        if (stdSelect.value === '10') {
            groupSelect.value = 'none'; // Set default to "None" for 10th standard
        }
    });
});
</script>


</html>
