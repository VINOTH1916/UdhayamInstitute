<?php
session_start();

// Check if attendance marking is allowed for today
$currentDate11th = date("Y-m-d");
$attendanceKey11th = "attend_sci_11th_" . $currentDate11th;

if (isset($_SESSION[$attendanceKey11th])) {
    $attendanceMarked = true; // Flag to indicate that attendance has been marked for today
} else {
    $attendanceMarked = false;
}

// Function to fetch 11th-grade science students from the database
function fetchEleventhGradeScienceStudents($conn) {
    $students = array(); 

    $query = "SELECT roll_no, student_name FROM students WHERE std = '11' AND name_of_grp = 'science'";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }

    return $students;
}

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

$eleventhGradeScienceStudents = fetchEleventhGradeScienceStudents($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mark 11th Grade Science Student Attendance</title>
    <script>
        // Function to disable the submit button
        function disableSubmitButton() {
            var submitButton = document.getElementById("submitBtn");
            submitButton.disabled = true;
            submitButton.value = "Attendance Already Marked";
        }
    </script>
</head>
<body>
    <h1>Mark 11th Grade Science Student Attendance</h1>

    <form action="process_attendance_sci_11th.php" method="POST" onsubmit="<?php echo $attendanceMarked ? 'disableSubmitButton()' : ''; ?>">
        <table>
            <thead>
                <tr>
                    <th>Student roll no</th>
                    <th>Student Name</th>
                    <th>Present</th>
                    <th>Absent</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($eleventhGradeScienceStudents as $student) {
                    echo '<tr>';
                    echo '<td>' . $student['roll_no'] . '</td>';
                    echo '<td>' . $student['student_name'] . '</td>';
                    echo '<td><input type="checkbox" name="attend_sci_11th[' . $student['roll_no'] . ']" value="present"></td>';
                    echo '<td><input type="checkbox" name="attend_sci_11th[' . $student['roll_no'] . ']" value="absent"></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <input type="submit" id="submitBtn" name="submit" value="<?php echo $attendanceMarked ? 'Attendance Already Marked' : 'Submit Attendance'; ?>" <?php echo $attendanceMarked ? 'disabled' : ''; ?>>
    </form>

    <form action="attendance_report.php" method="GET">
        <input type="submit" name="checkAttendance" value="Check Attendance Records">
    </form>

</body>
</html>
