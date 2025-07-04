<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "udhayam_institute";

// Create connection
$conn = new mysqli($servername, $username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch the teacher's record based on the email
    $sql = "SELECT * FROM teachers WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $teacher = $result->fetch_assoc();

            // Compare plain text passwords (not recommended for production)
            if ($password == $teacher['password']) {
                // Password is correct, set the session
                $_SESSION['teacher_id'] = $teacher['teacher_id'];
                $_SESSION['teacher_name'] = $teacher['name'];
                header("Location: teacher_dashboard.php");
                exit();
            } else {
                // Invalid password
                echo "Invalid email or password.";
            }
        } else {
            // No user found with the given email
            echo "Invalid email or password.";
        }

        $stmt->close();
    } else {
        echo "Error in preparing the SQL statement.";
    }
}

$conn->close();
?>
