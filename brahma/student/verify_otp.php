<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        form {
            width: 300px;
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

        input[type="password"] {
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

        .error-message {
            color: #ff0000;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $enteredOtp = $_POST["otp"];

            if (isset($_SESSION["otp"])) {
                $storedOtp = $_SESSION["otp"];

                if ($enteredOtp == $storedOtp) {
                    // The entered OTP is correct
                    // You can allow the user to reset their password here

                    // Display a password reset form
                    echo "<h1>OTP is correct. Reset Your Password</h1>";
                    echo "<form method='post' action='reset_password.php'>";
                    echo "<label for='new_password'>New Password:</label>";
                    echo "<input type='password' name='new_password' id='new_password' required>";
                    echo "<input type='submit' value='Reset Password'>";
                    echo "</form>";
                } else {
                    // The entered OTP is incorrect
                    echo "<div class='error-message'>Incorrect OTP. Please try again.</div>";
                }
            } else {
                echo "<div class='error-message'>Session expired. Please request a new OTP.</div>";
            }
        }
    ?>
</body>
</html>
