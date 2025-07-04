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
    $student_id = $_POST["student_id"];

    // Delete the student record from archived_students
    $sql = "DELETE FROM archived_students WHERE student_id='$student_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Archived student record deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    header("Location: archived_student_list.php");
    exit();
}

$conn->close();
?>
