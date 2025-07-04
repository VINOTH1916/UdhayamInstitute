<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" type="text/css" href="view_attendance.css">


<body>
    <h1>Admin Page</h1>
    <h1>View Student Attendance</h1>

    <div class="container">
  



    <form action="view_attendance.php" method="POST">
        <label for="class">Select Class:</label>
        <select name="class" id="class">
            <option value="10">10th Grade</option>
            <option value="11sci">11th Science</option>
            <option value="11arts">11th Arts</option>
            <option value="12sci">12th Science</option>
            <option value="12arts">12th Arts</option>
        </select>

    
        <label for="date">Select Date:</label>
        <input type="date" name="date" id="date" required>

        <label for="student_id">Enter Student Roll No:</label>
        <input type="text" name="student_id" id="student_id" required>

        <input type="submit" name="search" value="Search">
    </form>
</div>
    
</body>
</head>
</html>

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["search"])) {
    $class = $_POST["class"];
    $date = $_POST["date"];
    $student_id = $_POST["student_id"];

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

    // Create a query to fetch the student's name
    $nameQuery = "SELECT student_name FROM students WHERE roll_no = $student_id";

    // Execute the query to get the student's name
    $nameResult = mysqli_query($conn, $nameQuery);

    // Fetch the student's name
    $studentName = "Unknown"; // Default name if not found

    if (mysqli_num_rows($nameResult) > 0) {
        $row = mysqli_fetch_assoc($nameResult);
        $studentName = $row["student_name"];
    }

    // Create a query to fetch the attendance records for the selected student, class, and date
    $attendanceQuery = "";

    if ($class === "10") {
        $attendanceQuery .= "SELECT * FROM attend_10th";
    } elseif ($class === "11sci") {
        $attendanceQuery .= "SELECT * FROM attend_sci_11th";
    } elseif ($class === "11arts") {
        $attendanceQuery = "SELECT * FROM attend_art_11th";
    } elseif ($class === "12sci") {
        $attendanceQuery .= "SELECT * FROM attend_sci_12th";
    } elseif ($class === "12arts") {
        $attendanceQuery = "SELECT * FROM attend_art_12th";
    }


    $attendanceQuery .= " WHERE student_id = $student_id AND date = '$date'";

    // Execute the query to get attendance records
    $attendanceResult = mysqli_query($conn, $attendanceQuery);

    // Display the attendance records
    echo "<h2>Attendance Records for Student: $studentName (Roll No: $student_id)</h2>";

    if (mysqli_num_rows($attendanceResult) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Date</th><th>Status</th></tr>";

        while ($row = mysqli_fetch_assoc($attendanceResult)) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["date"] . "</td>";
            echo "<td>" . $row["status"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No attendance records found for $studentName (Roll No: $student_id) on $date.";
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
