<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "udhayam_institute";
$conn = new mysqli($servername, $username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_id = $_POST["teacher_id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phone_number = $_POST["phone_number"];
    $date_of_join = $_POST["date_of_join"];


    // Update teacher profile
    $sql = "UPDATE teachers SET 
    name='$name',
    email='$email',
    phone_number='$phone_number',
    date_of_join='$date_of_join',
    password='$password'
    WHERE teacher_id='$teacher_id'";


    if ($conn->query($sql) === TRUE) {
        echo "Teacher information updated successfully!";
        header("Location: teacher_list.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
