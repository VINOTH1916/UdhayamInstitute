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

    // Delete the teacher record from archived_teachers
    $sql = "DELETE FROM archived_teachers WHERE teacher_id='$teacher_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Teacher record deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    header("Location: archived_teacher_list.php");
    exit();
}

$conn->close();
?>
