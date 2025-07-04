<?php
session_start();

// Replace with your actual database connection code
$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "udhayam_institute";

$conn = new mysqli($servername, $username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roll_no = $_POST["roll_no"];
    $student_name = $_POST["student_name"];
    $password = $_POST["password"];

    // Perform validation and database query here (for simplicity, we use a placeholder condition)
    $query = "SELECT * FROM students WHERE roll_no = '$roll_no' AND student_name = '$student_name' AND password = '$password'";

    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        // Login successful
        $_SESSION["student_name"] = $student_name;
        $_SESSION["roll_no"]=$roll_no;
    
        header("Location:dashboard.php");
        exit;
    } else {
        // Login failed
        echo "Login failed. Please check your credentials.";
    }
}

$conn->close();
?>
