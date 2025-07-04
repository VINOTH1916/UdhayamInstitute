<?php
session_start();

// Replace with your actual database connection code
$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "udhayam_institute";

$conn = new mysqli($servername, $username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the student's ID from the session (you need to set this when the student logs in)
$student_id = $_SESSION["roll_no"];

// Function to determine the student's class based on the 'std' and 'name_of_grp' columns
function getStudentClass($conn, $student_id) {
    $query = "SELECT std, name_of_grp FROM students WHERE roll_no = $student_id";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $std = $row["std"];
        $name_of_grp = $row["name_of_grp"];

        // Define a mapping from 'std' and 'name_of_grp' values to class names
        $classMappings = [
            '10' => '10th',
            '11' => '11th',
            '12' => '12th',
            'Arts' => 'arts',
            'Science' => 'science',
            // Add more mappings as needed
        ];

        // Use the mapping to determine the class
        if (isset($classMappings[$std]) && isset($classMappings[$name_of_grp])) {
            return $classMappings[$std] . '_' . $classMappings[$name_of_grp];
        }
    }

    return ""; // Return an empty string if the class cannot be determined
}

// Determine the student's class based on the 'std' and 'name_of_grp' columns
$studentClass = getStudentClass($conn, $student_id);

// Check if the user is logged in as a student
if (!isset($_SESSION["student_name"]) || empty($studentClass)) {
    header("Location: login.php"); // Redirect to the login page if not logged in or class is not determined
    exit;
}

// Function to get attendance data for a specific student and class
function getAttendanceData($conn, $student_id, $class) {
    $attendanceData = [];

    // Adjust the table name based on the student's class
    $table = "attend_" . $class;

    // You will need to adapt this query to your database schema
    $query = "SELECT date, status FROM $table WHERE student_id = $student_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $date = $row["date"];
            $status = $row["status"];
            $attendanceData[$date] = $status;
        }
    }

    return $attendanceData;
}

// Get attendance data for the logged-in student and their class
$attendanceData = getAttendanceData($conn, $student_id, $studentClass);

// Function to generate a calendar
function generateCalendar($year, $month, $attendanceData) {
    // (Code for generating the calendar - see previous examples)
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Check Attendance</title>
    <style>
        /* Define colors for attendance status */
        .present { background-color: green; }
        .absent { background-color: red; }
        .holiday { background-color: blue; }

        /* Other CSS styling for the calendar */
        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION["student_name"]; ?></h1>
    <h2>Attendance for <?php echo date("F Y"); ?></h2>

    <!-- Display the generated calendar -->
    <?php echo generateCalendar(date("Y"), date("m"), $attendanceData); ?>

    <p><a href="logout.php">Logout</a></p>
</body>
</html>
