<?php
session_start();

// Check if attendance marking is allowed for today
$currentDate10th = date("Y-m-d");
$attendanceKey10th = "attendance_" . $currentDate10th;

if (isset($_SESSION[$attendanceKey10th])) {
    $attendanceMarked10th = true; // Flag to indicate that attendance has been marked for today
} else {
    $attendanceMarked10th = false;
}

// Function to fetch 10th-grade student data from the database
function fetchTenthGradeStudents($conn) {
    $students = array();

    $query = "SELECT roll_no, student_name FROM students WHERE std = '10'";
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

$students = fetchTenthGradeStudents($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark 10th Grade Student Attendance</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        header, nav, footer {
            background-color: #333;
            color: #fff;
            text-align: center;
        }

        header, nav {
            padding: 10px 0;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
        }

        .present-checkbox,
        .absent-checkbox {
            margin-right: 10px;
        }

        .present-checkbox input[type="checkbox"],
        .absent-checkbox input[type="checkbox"] {
            display: none;
        }

        .present-checkbox label,
        .absent-checkbox label {
            cursor: pointer;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
        }

        .present-checkbox input[type="checkbox"]:checked+label {
            background-color: #4CAF50;
            color: white;
        }

        .absent-checkbox input[type="checkbox"]:checked+label {
            background-color: #f44336;
            color: white;
        }

        #submitBtn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        #submitBtn:disabled {
            background-color: #aaa;
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>Mark 10th Grade Student Attendance</h1>
        </header>

        <nav>
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Contact</a>
        </nav>

        <form action="process_attendance_10th.php" method="POST"
            onsubmit="<?php echo $attendanceMarked10th ? 'disableSubmitButton()' : ''; ?>">
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
                    foreach ($students as $student) {
                        echo '<tr>';
                        echo '<td>' . $student['roll_no'] . '</td>';
                        echo '<td>' . $student['student_name'] . '</td>';
                        echo '<td class="checkbox-group">';
                        // Inside the foreach loop in mark_attendance_10th.php
                      echo '<div class="present-checkbox"><input type="checkbox" id="present_' . $student['roll_no'] . '" name="attendance[' . $student['roll_no'] . ']" value="present" onclick="handleCheckboxClick(this, \'absent_' . $student['roll_no'] . '\')"> <label for="present_' . $student['roll_no'] . '">Present</label></div>';
                      echo '<div class="absent-checkbox"><input type="checkbox" id="absent_' . $student['roll_no'] . '" name="attendance[' . $student['roll_no'] . ']" value="absent" onclick="handleCheckboxClick(this, \'present_' . $student['roll_no'] . '\')"> <label for="absent_' . $student['roll_no'] . '">Absent</label></div>';

                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <input type="submit" id="submitBtn"
                value="<?php echo $attendanceMarked10th ? 'Attendance Already Marked' : 'Submit Attendance'; ?>"
                <?php echo $attendanceMarked10th ? 'disabled' : ''; ?>>
        </form>
    </div>

    <div class="container">
        <header>
            <h1>Mark 11th Grade Student Attendance</h1>
        </header>

        <nav>
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Contact</a>
        </nav>

        <form action="process_attendance_10th.php" method="POST"
            onsubmit="<?php echo $attendanceMarked10th ? 'disableSubmitButton()' : ''; ?>">
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
                    foreach ($students as $student) {
                        echo '<tr>';
                        echo '<td>' . $student['roll_no'] . '</td>';
                        echo '<td>' . $student['student_name'] . '</td>';
                        echo '<td class="checkbox-group">';
                        // Inside the foreach loop in mark_attendance_10th.php
                      echo '<div class="present-checkbox"><input type="checkbox" id="present_' . $student['roll_no'] . '" name="attendance[' . $student['roll_no'] . ']" value="present" onclick="handleCheckboxClick(this, \'absent_' . $student['roll_no'] . '\')"> <label for="present_' . $student['roll_no'] . '">Present</label></div>';
                      echo '<div class="absent-checkbox"><input type="checkbox" id="absent_' . $student['roll_no'] . '" name="attendance[' . $student['roll_no'] . ']" value="absent" onclick="handleCheckboxClick(this, \'present_' . $student['roll_no'] . '\')"> <label for="absent_' . $student['roll_no'] . '">Absent</label></div>';

                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <input type="submit" id="submitBtn"
                value="<?php echo $attendanceMarked10th ? 'Attendance Already Marked' : 'Submit Attendance'; ?>"
                <?php echo $attendanceMarked10th ? 'disabled' : ''; ?>>
        </form>
    </div>

    <footer>
        &copy; 2023 Your School Name. All rights reserved.
    </footer>

    <script>
        function disableSubmitButton() {
            document.getElementById("submitBtn").disabled = true;
        }



    </script>

<script>
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
