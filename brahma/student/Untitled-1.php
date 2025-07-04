<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["roll_no"])) {
    header("Location: login.php");
    exit();
}

$roll_no = $_SESSION["roll_no"];

// Replace with your actual database connection code
$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "udhayam_institute";

$conn = new mysqli($servername, $username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current month and year
$currentMonth = date("m");
$currentYear = date("Y");

// Get the number of days in the current month
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

// Construct the attendance_table variable
$attendance_table = "attend" . "_";

if (substr($roll_no, 4, 2) == "10") {
    $attendance_table .= "10th";
} elseif (substr($roll_no, 6, 2) == "00") {
    $attendance_table .= "arts_" . strtolower(substr($roll_no, 4, 2)) . "th";
} else {
    $attendance_table .= "sci_" . strtolower(substr($roll_no, 4, 2)) . "th";
}

// Get attendance data for the current month
$attendanceData = [];
for ($day = 1; $day <= $daysInMonth; $day++) {
    $date = "$currentYear-$currentMonth-" . str_pad($day, 2, "0", STR_PAD_LEFT);
    $attendanceQuery = "SELECT status FROM $attendance_table WHERE student_id = '$roll_no' AND date = '$date'";
    $attendanceResult = $conn->query($attendanceQuery);
    if ($attendanceResult->num_rows > 0) {
        $attendanceRow = $attendanceResult->fetch_assoc();
        $attendanceData[$day] = $attendanceRow["status"];
    } else {
        $attendanceData[$day] = "absent";
    }
}

$attendance_query = "SELECT COUNT(*) as total, SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present FROM $attendance_table WHERE student_id = '$roll_no'";
$attendance_result = $conn->query($attendance_query);
$attendance_data = $attendance_result->fetch_assoc();
$total_days = $attendance_data["total"];
$present_days = $attendance_data["present"];

// Calculate attendance percentage
if ($total_days > 0) {
    $attendance_percentage = ($present_days / $total_days) * 100;
} else {
    $attendance_percentage = 0;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            text-align: center;
        }

        .day {
            font-weight: bold;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .present {
            background-color: #8BC34A;
            color: #fff;
        }

        .absent {
            background-color: #F44336;
            color: #fff;
        }

        .holiday {
            background-color: #2196F3;
            color: #fff;
        }

        .live-time {
            text-align: right;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>


</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Welcome, <?php echo $_SESSION["student_name"]; ?>!</h2>
            <p>Attendance for <?php echo date("F Y"); ?></p>
        </div>
        <div class="calendar">
            <?php
            $daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            // Display days of the week
            foreach ($daysOfWeek as $day) {
                echo '<div class="day">' . $day . '</div>';
            }

            // Get attendance data for the current month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $status = isset($attendanceData[$day]) ? $attendanceData[$day] : "absent";
                $class = "";
                switch ($status) {
                    case "present":
                        $class = "present";
                        break;
                    case "absent":
                        $class = "absent";
                        break;
                    default:
                        $class = "holiday";
                        break;
                }
                echo '<div class="day ' . $class . '">' . $day . '</div>';
            }
            ?>
        </div>
        <div class="live-time" id="live-time">
            Live Time: <!-- JavaScript will update this part -->
        </div>

        <div class="result">
            <?php
            if ($attendance_percentage > 0) {
                echo "Attendance Percentage: " . number_format($attendance_percentage, 2) . "%";
            } else {
                echo "No attendance data available for the student.";
            }
            ?>
        </div>

    </div>
</body>

<script>
        // Function to update live time every second
        function updateLiveTime() {
            var currentTime = new Date();
            var daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            var liveTime = "Live Time: " + daysOfWeek[currentTime.getDay()] + ", " + currentTime.getDate() + " " + monthNames[currentTime.getMonth()] + " " + currentTime.getFullYear() + " " + currentTime.getHours() + ":" + (currentTime.getMinutes() < 10 ? '0' : '') + currentTime.getMinutes() + ":" + (currentTime.getSeconds() < 10 ? '0' : '') + currentTime.getSeconds();
            document.getElementById("live-time").textContent = liveTime;
        }

        // Update live time initially and then every second
        updateLiveTime();
        setInterval(updateLiveTime, 1000);
    </script>

</html>
