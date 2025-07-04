<?php
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if attendance marking is allowed for today
    $currentDate10th = date("Y-m-d");
    $attendanceKey10th = "attendance_" . $currentDate10th;

    if (!isset($_SESSION[$attendanceKey10th])) {
        // Proceed to update attendance only if it has not been marked already for today
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "udhayam_institute";

        // Create a database connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check the database connection
        if (!$conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        // Process attendance data submitted via the form
        if (isset($_POST['attendance']) && is_array($_POST['attendance'])) {
            foreach ($_POST['attendance'] as $roll_no => $status) {
                // Sanitize data to prevent SQL injection
                $roll_no = mysqli_real_escape_string($conn, $roll_no);
                $status = mysqli_real_escape_string($conn, $status);

                // Update attendance records in the database
                $query = "INSERT INTO attend_10th (student_id, status, date) VALUES ('$roll_no', '$status', '$currentDate10th')";
                mysqli_query($conn, $query);
            }

            // Mark attendance as already marked for the current date
            $_SESSION[$attendanceKey10th] = true;
        }

        // Close the database connection
        mysqli_close($conn);
    }
}

// Redirect back to the attendance marking page
header("Location: attendance_10th.php");
exit();
?>
