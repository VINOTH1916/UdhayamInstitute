<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $_POST['otp'];

    if ($otp == $_SESSION['otp']) {
        // Check OTP expiry time
        $conn = new mysqli("localhost", "root", "", "udhayam_institute");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $email = $_SESSION['admin_email'];
        $sql = "SELECT otp_expiry FROM admin WHERE email='$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (new DateTime() < new DateTime($row['otp_expiry'])) {
                // OTP is valid
                echo "Login successful!";
                // Redirect to admin dashboard
                // header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "OTP has expired.";
            }
        } else {
            echo "Invalid OTP.";
        }

        $conn->close();
    } else {
        echo "Invalid OTP.";
    }
}
?>
