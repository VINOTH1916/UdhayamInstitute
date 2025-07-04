<?php
// Perform database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "udhayam_institute";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch and display attendance records grouped by date
$query = "SELECT DISTINCT date FROM student_attendance";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $date = $row["date"];
        echo "<h2>Attendance for Date: $date</h2>";

        // Fetch attendance records for the current date with student names
        $attendanceQuery = "SELECT sa.student_id, sa.status, s.student_name FROM student_attendance sa
                            INNER JOIN students s ON sa.student_id = s.roll_no
                            WHERE sa.date = '$date'";
        $attendanceResult = mysqli_query($conn, $attendanceQuery);

        if (mysqli_num_rows($attendanceResult) > 0) {
            echo "<table>";
            echo "<tr><th>Student ID</th><th>Student Name</th><th>Status</th></tr>";
            while ($attendanceRow = mysqli_fetch_assoc($attendanceResult)) {
                $student_id = $attendanceRow["student_id"];
                $student_name = $attendanceRow["student_name"];
                $status = $attendanceRow["status"];
                echo "<tr><td>$student_id</td><td>$student_name</td><td>$status</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No attendance records found for this date.";
        }
    }
} else {
    echo "No attendance records found.";
}

// Close the database connection
mysqli_close($conn);
?>
