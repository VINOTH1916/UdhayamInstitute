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
    $marks = $_POST['marks'];
    $total_marks = $_POST['total_marks'];
    $dates = $_POST['date'];

    foreach ($marks as $roll_no => $mark) {
        $total_mark = $total_marks[$roll_no];
        $date = $dates[$roll_no];

        $sql = "UPDATE marks 
                SET marks='$mark', total_marks='$total_mark', date='$date' 
                WHERE roll_no='$roll_no' AND std='$std' AND name_of_grp='$name_of_grp' AND test_name='$test_name' AND subject_name='$subject_name'";

        if ($conn->query($sql) === TRUE) {
            echo "Marks for roll number $roll_no updated successfully.<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    header("Location: success.php");
    exit();
}

$conn->close();
?>
