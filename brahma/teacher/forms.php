<?php
// Include the PHPMailer dependencies
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require 'C:/vetho/htdocs/PHPMailer-master/PHPMailer-master/src/Exception.php';

// Function to generate the password based on std
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

$roll_no = ""; // Initialize the variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form (sanitize or use prepared statements for security)
    $student_name = $_POST["student_name"];
    $date_of_birth = $_POST["date_of_birth"];
    $std = $_POST["std"];
    $email = $_POST["email"];
    $ph_no = $_POST["ph_no"];
    $name_of_grp = $_POST["name_of_grp"];
    $age = $_POST["age"];
    $date_of_join = $_POST["date_of_join"];
    $mat_cbsc = $_POST["mat_cbsc"];
    $blood_grp = $_POST["blood_grp"];
    $school_name = $_POST["school_name"];
    
    // Generate the password
    $generatedPassword = generatePassword($student_name, $date_of_birth, $std);

    // Perform database connection and INSERT query here
    $servername = "localhost";
    $username = "root";
    $db_password = ""; // Use a different variable name for the database password
    $dbname = "udhayam_institute";

    // Create a connection
    $conn = new mysqli($servername, $username, $db_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL INSERT statement
    $sql = "INSERT INTO students (student_name, date_of_birth, std, email, ph_no, name_of_grp, age, date_of_join, mat_cbsc, blood_grp, school_name, password)
            VALUES ('$student_name', '$date_of_birth', '$std', '$email', '$ph_no', '$name_of_grp', '$age', '$date_of_join', '$mat_cbsc', '$blood_grp', '$school_name', '$generatedPassword')";

    // After the database insertion is successful
    if ($conn->query($sql) === TRUE) {
        $newStudentId = $conn->insert_id; // Get the unique ID of the new student
        $roll_no = generateRollNumber($std, $name_of_grp, $conn); // Generate the roll number

        // Update the database to set the roll_no for the newly inserted student
        $updateRollNoSql = "UPDATE students SET roll_no = '$roll_no' WHERE student_id = $newStudentId";
        if ($conn->query($updateRollNoSql) === TRUE) {
            // Prepare the email data
            $to = $_POST["email"];
            $subject = "Your Student Information at UDHAYAM INSTITUTE";
            $message = "Dear " . $_POST["student_name"] . ",\n\n";
            $message .= "Welcome to UDHAYAM INSTITUTE! We are delighted to have you as a part of our esteemed institution.\n\n";
            $message .= "Here is your student information:\n";
            $message .= "Roll Number: " . $roll_no . "\n";
            $message .= "Student Name: " . $_POST["student_name"] . "\n";
            $message .= "Standard: " . $_POST["std"] . "\n";
            $message .= "Password: " . $generatedPassword . "\n\n";
            $message .= "UDHAYAM INSTITUTE is committed to providing a nurturing and challenging environment to help you achieve your academic and personal goals. We offer a variety of programs and activities to support your growth and learning.\n\n";
            $message .= "If you have any questions or need further assistance, please do not hesitate to contact our administrative office.\n\n";
            $message .= "Best regards,\n";
            $message .= "UDHAYAM INSTITUTE\n";
            $message .= "Contact Us: +91 9384650430 | info@udhayaminstitute.co.in\n";
            $message .= "Visit Us: www.udhayaminstitute.co.in\n";

            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Your SMTP server
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
                $mail->Body = $message;

                // Send email
                $mail->send();
                $successMessage = "Record inserted successfully, and an email has been sent.<br>";
                $successMessage .= "Student Name: " . $student_name . "<br>";
                $successMessage .= "Roll Number: " . $roll_no;
            } catch (Exception $e) {
                echo "Error sending email: " . $e->getMessage();
            }
        } else {
            echo "Error updating roll number: " . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Insert Student Data</title>
    <link rel="stylesheet" type="text/css" href="formcss.css">
</head>
<body>


<div style="text-align: center; margin-bottom: 20px;">
        <?php
        if (isset($successMessage)) {
            echo '<div style="background-color: white; color: green; padding: 10px; border:1px solid red; margin-bottom: 10px;">' . $successMessage . '</div>';
        }
        ?>
    </div>

    <h1>Insert Student Data</h1>
    <div class="container">

    <div id="preloader" class="preloader">
            <video autoplay loop muted height="200px" width="auto">
                <source src="loading-video.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    <form id="student-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

            <h2>UDHAYAM INSTITUTE</h2>
            <label for="student_name">Student Name:</label>
            <input type="text" id="student_name" name="student_name" required>

            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" required>

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
            <select id="name_of_grp" name ="name_of_grp" required>
                <option value="art">Art</option>
                <option value="sci">Science</option>
                <option value="none">None</option>
            </select>

            <label for="age">Age (15-20):</label>
            <input type="number" id="age" name="age" required min="15" max="20">

            <label for="date_of_join">Date of Join:</label>
            <input type="date" id="date_of_join" name="date_of_join" required>

            <label for="mat_cbsc">Matriculation/CBSE:</label>
            <select id="mat_cbsc" name="mat_cbsc" required>
                <option value="mat">Matriculation</option>
                <option value="cbsc">CBSE</option>
            </select>

            <label for="blood_grp">Blood Group:</label>
<select id="blood_grp" name="blood_grp" required>
    <option value="" disabled selected>Select your blood group</option>
    <option value="A+">A+</option>
    <option value="A-">A-</option>
    <option value="B+">B+</option>
    <option value="B-">B-</option>
    <option value="AB+">AB+</option>
    <option value="AB-">AB-</option>
    <option value="O+">O+</option>
    <option value="O-">O-</option>
</select>

            <label for="school_name">School Name:</label>
            <input type="text" id="school_name" name="school_name" required>

            <input type="submit" value="Insert">
            
        </form>
        
    </div>
   

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
});
</script>

</body>
</html>
