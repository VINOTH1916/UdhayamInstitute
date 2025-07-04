<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if attendance marking is allowed for today
$currentDate = date("Y-m-d");
$attendanceKey = "attend_art_12th_" . $currentDate;

if (isset($_SESSION[$attendanceKey])) {
    $attendanceMarked = true; // Flag to indicate that attendance has been marked for today
} else {
    $attendanceMarked = false;
}

// Function to fetch 12th-grade arts students from the database
function attend_art_12thh($conn) {
    $students = array(); 

    $query = "SELECT student_id,roll_no, student_name FROM students WHERE std = '12' AND name_of_grp = 'art'";
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

$attend_art_12thh =  attend_art_12thh($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="attendance.css">
    <title>Mark 12th Grade Arts Student Attendance</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>12th Grade Arts Student Attendance</h1>
        </header>

        <nav>
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Contact</a>
        </nav>

        <form action="process_attendance_art_12th.php" method="POST"
            onsubmit="<?php echo $attendanceMarked ? 'disableSubmitButton()' : ''; ?>">
            
            <?php
            // Display number of students present today if available
            $sql = "SELECT COUNT(*) AS present_count FROM attend_art_12th WHERE date = '$currentDate' AND status = 'present'";
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
                        <th>Student Roll No</th>
                        <th>Student Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through the student array to display attendance checkboxes
                    foreach ($attend_art_12thh as $student) {
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
            
            <input type="submit" id="submitBtn" value="<?php echo $attendanceMarked ? 'Attendance Already Marked' : 'Submit Attendance'; ?>"
                <?php echo $attendanceMarked ? 'disabled' : ''; ?>>

            <a href="attend_art_11th.php" class="button">11th Grade Arts</a>
            <a href="attend_sci_11th.php" class="button">11th Grade Science</a>
            <a href="attend_sci_12th.php" class="button">12th Grade Science</a>
            <a href="attendance_10th.php" class="button">10th Grade</a>
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
