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
    } elseif ($class === "11") {
        $attendanceQuery .= "SELECT * FROM attend_sci_11th";
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
