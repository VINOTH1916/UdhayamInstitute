<?php
session_start();

if (!isset($_SESSION['forgot_email']) || !isset($_SESSION['forgot_otp'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp_entered = $_POST['otp'];
    $new_password = $_POST['new_password'];

    // Validate OTP entered
    if ($otp_entered == $_SESSION['forgot_otp']) {
        // Update password in database
        $conn = new mysqli("localhost", "root", "", "udhayam_institute");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $email = $_SESSION['forgot_email'];
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash new password

        $sql_update = "UPDATE admin SET password='$hashed_password', otp=NULL, otp_expiry=NULL WHERE email='$email'";
        if ($conn->query($sql_update) === TRUE) {
            echo "Password updated successfully!";
            unset($_SESSION['forgot_email']);
            unset($_SESSION['forgot_otp']);
            header("Location: admin.php"); // Redirect to login page
            exit();
        } else {
            echo "Error updating password: " . $conn->error;
        }

        $conn->close();
    } else {
        echo "Invalid OTP. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
body {
  font-family: Arial, Helvetica, sans-serif;
  background-color: black;
  color: white;
  text-align: center;
}

.container {
  padding: 16px;
  background-color: white;
  color: black;
  max-width: 500px;
  margin: auto;
  border-radius: 10px;
}

input[type=text], input[type=password] {
  width: 200px;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
  border-radius: 5px;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
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
  border-radius: 5px;
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
    <div class="container">
        <h2>Reset Password</h2>
        <form method="post">
            <label>Enter OTP sent to your email:</label>
            <input type="number" name="otp" size="6"><br><br>
            <label>Enter new password:</label>
<input type="password" id="new_password" name="new_password" required oninput="checkPasswordStrength()"><br><br>
<div id="password-strength-status"></div>
<input class="registerbtn" type="submit" value="Reset Password" onclick="return validatePassword()">

        </form>
    </div>
</body>

<script>
function checkPasswordStrength() {
    var password = document.getElementById('new_password').value;
    var strengthStatus = document.getElementById('password-strength-status');

    // Regular expressions to check the strength of the password
    var strongPassword = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\\$%\\^&\\*])(?=.{8,})");
    var mediumPassword = new RegExp("^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})");

    if (strongPassword.test(password)) {
        strengthStatus.style.color = "green";
        strengthStatus.textContent = "Strong password";
    } else if (mediumPassword.test(password)) {
        strengthStatus.style.color = "orange";
        strengthStatus.textContent = "Medium strength password";
    } else {
        strengthStatus.style.color = "red";
        strengthStatus.textContent = "Weak password";
    }
}

function validatePassword() {
    var password = document.getElementById('new_password').value;
    var strengthStatus = document.getElementById('password-strength-status');

    // Password strength check
    if (strengthStatus.textContent !== "Strong password") {
        alert("Please enter a stronger password.");
        return false;
    }
    return true;
}
</script>


</html>
