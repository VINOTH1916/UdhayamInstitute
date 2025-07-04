<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST["new_password"];

    $servername = "localhost";
    $username = "root";
    $db_password = ""; // Use a different variable name for the database password
    $dbname = "udhayam_institute";

    // Create a connection
    $conn = new mysqli($servername, $username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the user's password in the database
    $roll_no = $_SESSION["roll_no"]; // You need to retrieve the user's ID
    $sql = "UPDATE students SET password = '$newPassword' WHERE roll_no = $roll_no";

    if ($conn->query($sql) === TRUE) {
        echo "Password reset successfully.";
    } else {
        echo "Error updating password: " . $conn->error;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
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

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Password Reset</h1>
    <form method="post" action="reset_password.php">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required>
        <input type="submit" value="Reset Password">
    </form>

    <br>

    <a href="student.login.html">Go Back to Login</a>
</body>
</html>

