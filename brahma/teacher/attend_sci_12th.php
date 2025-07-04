<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if attendance marking is allowed for today
$currentDate = date("Y-m-d");
$attendanceKey = "attend_sci_12th_" . $currentDate;

if (isset($_SESSION[$attendanceKey])) {
    $attendanceMarked = true; // Flag to indicate that attendance has been marked for today
} else {
    $attendanceMarked = false;
}

// Function to fetch 11th-grade science students from the database
function fetchEleventhGradeScienceStudents12($conn) {
    $students = array(); 

    $query = "SELECT student_id,roll_no, student_name FROM students WHERE std = '12' AND name_of_grp = 'sci'";
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

$eleventhGradeScienceStudents12 = fetchEleventhGradeScienceStudents12($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="attendance.css">
    <title>Mark 12th Grade Science Student Attendance</title>
</head>

<body>
    <div class="container">
        <header>
            <h1>12th Grade Science Student Attendance</h1>
        </header>

        <nav>
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Contact</a>
        </nav>

        <form action="process_attendance_sci_12th.php" method="POST"
            onsubmit="<?php echo $attendanceMarked ? 'disableSubmitButton()' : ''; ?>">

            <?php
        $currentDate = date("Y-m-d"); // Get the current date in MySQL format (YYYY-MM-DD)

$sql = "SELECT COUNT(*) AS present_count FROM attend_sci_12th WHERE date = '$currentDate' AND status = 'present'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $presentCount = $row['present_count'];
    echo '<div style="font-size: 15px; font-weight: bold; color: green; border: 2px solid black; background-color:  #3e3e3ef1; padding: 10px;">Number of students present today: ' . $presentCount . '</div>';


} else {
    echo "No records found for today.";
}
?>

            <table>
                <thead>
                    <tr>
                        <th>Student roll no</th>
                        <th>Student Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Your PHP logic to fetch and display student data goes here
                    foreach ($eleventhGradeScienceStudents12 as $student) {
                        echo '<tr>';
                        echo '<td>' . $student['roll_no'] . '</td>';
                        echo '<td>' . $student['student_name'] . '</td>';
                        echo '<td class="checkbox-group">';
                        echo '<div class="present-checkbox"><input type="checkbox" id="present_' . $student['student_id'] . '" name="attendance[' . $student['student_id'] . ']" value="present" onclick="handleCheckboxClick(this, \'absent_' . $student['student_id'] . '\')"> <label for="present_' . $student['student_id'] . '">Present</label></div>';
                        echo '<div class="absent-checkbox"><input type="checkbox" id="absent_' . $student['student_id'] . '" name="attendance[' . $student['student_id'] . ']" value="absent" onclick="handleCheckboxClick(this, \'present_' . $student['student_id'] . '\')"> <label for="absent_' . $student['student_id'] . '">Absent</label></div>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <input type="submit" id="submitBtn"
                value="<?php echo $attendanceMarked ? 'Attendance Already Marked' : 'Submit Attendance'; ?>"
                <?php echo $attendanceMarked ? 'disabled' : ''; ?>>

<a href="attend_art_11th.php" class="button">11th arts</a>
<a href="attend_art_12th.php" class="button">12th arts</a>
<a href="attend_sci_11th.php" class="button">11th science</a>
<a href="attendance_10th.php" class="button">10th grade</a>
        </form>
       

    </div>

    <footer>
        &copy; 2023 Your School Name. All rights reserved.
    </footer>

    <script>
        function disableSubmitButton() {
            document.getElementById("submitBtn").disabled = true;
        }

        function handleCheckboxClick(checkbox, otherCheckboxId) {
            var otherCheckbox = document.getElementById(otherCheckboxId);
            if (checkbox.checked) {
                otherCheckbox.disabled = true;
            } else {
                otherCheckbox.disabled = false;
            }
        }
    </script>
</body>

</html>
