<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'C:/xampp/htdocs/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require 'C:/xampp/htdocs/PHPMailer-master/PHPMailer-master/src/Exception.php';


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["send_message"])) {
    $class = $_POST["class"];
    $message = $_POST["message"];
    $reason = $_POST["reason"];

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "udhayam_institute";

    // Establish a database connection (if needed for retrieving student emails)
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create a function to send email based on class or all students
    function sendEmail($to, $subject, $message) {
        $mail = new PHPMailer(true);

        try {

            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'udhayaminstitute6@gmail.com';
            $mail->Password = 'ipqx kzlt ktxz wilw';   //(bloghub64@gmail.com)'ndit aptr qzwq wjod';//'tyit yaiu lcpj tfvh';
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
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Determine the recipients based on the selected class
    $recipients = array();

    if ($class === "all") {
        // Retrieve all student emails from the database (if needed)
        $sql = "SELECT email FROM students";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $recipients[] = $row["email"];
            }
        }
    } else {
        // Determine recipients based on the selected class
        $sql = "SELECT email FROM students WHERE std = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $class);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $recipients[] = $row["email"];
            }
        }
        $stmt->close();
    }

    // Send the message to selected recipients
    foreach ($recipients as $recipientEmail) {
        $subject = "Important Message: $reason";
        if (sendEmail($recipientEmail, $subject, $message)) {
            echo "Message sent to $recipientEmail successfully.<br>";
        } else {
            echo "Failed to send message to $recipientEmail.<br>";
        }
    }

    // Close the database connection
    $conn->close();
}
?>
