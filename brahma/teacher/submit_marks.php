<?php
session_start();

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "udhayam_institute";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $std = $_POST['std'];
    $name_of_grp = $_POST['name_of_grp'];
    $test_name = $_POST['test_name'];
    $subject_name = $_POST['subject_name'];
    $total_marks = $_POST['total_marks'];
    $date = $_POST['date'];
    $marks = $_POST['marks'];

    foreach ($marks as $roll_no => $mark) {
        $sql = "INSERT INTO marks (roll_no, std, name_of_grp, test_name, subject_name, marks, total_marks, date)
                VALUES ('$roll_no', '$std', '$name_of_grp', '$test_name', '$subject_name', '$mark', '$total_marks', '$date')";

        if ($conn->query($sql) === TRUE) {
            echo "Marks for roll number $roll_no added successfully.<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    header("Location: add_mark.html");
    exit();
}

$conn->close();
?>
