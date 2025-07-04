<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "udhayam_institute";

    // Establish a database connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check the database connection
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Retrieve student data from the "students" table for 11th-grade science students
    $sql = "SELECT roll_no, student_name FROM students WHERE std = '11' AND name_of_grp = 'Science'";
    $result = mysqli_query($conn, $sql);

    $students = array();

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Store student data in the $students array
            $students[$row["roll_no"]] = $row["student_name"];
        }
    } else {
        echo "No 11th-grade science student data found.";
        exit;
    }

    // Check if attendance has already been marked for today and for the same students
    $currentDate11th = date("Y-m-d");
    $attendanceKey11th = "attendance_" . $currentDate11th;

    if (isset($_SESSION[$attendanceKey11th])) {
        echo "Attendance has already been marked for today.";
        exit;
    }

    // Function to add attendance records to the database
    function addAttendance($conn, $student_id, $status) {
        $date = date("Y-m-d");
        $query = "INSERT INTO attend_art_11th(student_id, date, status) VALUES ($student_id, '$date', '$status')";
        mysqli_query($conn, $query);
    }

    foreach ($_POST["attendance"] as $student_id => $status) {
        // Check if the student_id is valid
        if (array_key_exists($student_id, $students)) {
            addAttendance($conn, $student_id, $status);
        }
    }

    // Set the session variable to mark attendance as done for today and for the same students
    $_SESSION[$attendanceKey11th] = true;

    // Close the database connection
    mysqli_close($conn);

    // Redirect to the attendance marking page after successful submission
    header("Location: attend_art_11th.php");
    exit;
}
?>
