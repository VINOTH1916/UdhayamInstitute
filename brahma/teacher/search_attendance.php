<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "udhayam_institute";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$year = isset($_POST['year']) ? $_POST['year'] : '';
$std = isset($_POST['std']) ? $_POST['std'] : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';
$roll_no = isset($_POST['roll_no']) ? $_POST['roll_no'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = "";

    switch ($std) {
        case "art_11th":
            $query .= "SELECT * FROM archived_attend_art_11th WHERE 1=1";
            break;
        case "art_12th":
            $query .= "SELECT * FROM archived_attend_art_12th WHERE 1=1";
            break;
        case "sci_11th":
            $query .= "SELECT * FROM archived_attend_sci_11th WHERE 1=1";
            break;
        case "sci_12th":
            $query .= "SELECT * FROM archived_attend_sci_12th WHERE 1=1";
            break;
        case "10th":
            $query .= "SELECT * FROM archived_attend_10th WHERE 1=1";
            break;
        default:
            echo "Invalid standard selected.";
            exit();
    }

    if (!empty($year)) {
        $query .= " AND YEAR(date) = '$year'";
    }

    if (!empty($date)) {
        $query .= " AND date = '$date'";
    }

    if (!empty($roll_no)) {
        $query .= " AND roll_no = '$roll_no'";
    }

    $result = $conn->query($query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Attendance Records</title>
    <link rel="stylesheet" type="text/css" href="view_attendance.css">
</head>
<body>
    <h1>Search Attendance Records</h1>
    <button onclick="window.location.href='admin.php'" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 10px 0;">Back</button>

    <form method="POST" action="">
        <label for="year">Year:</label>
        <input type="text" id="year" name="year" value="<?php echo htmlspecialchars($year); ?>"><br><br>

        <label for="std">Standard:</label>
        <select id="std" name="std">
            <option value="">Select</option>
            <option value="art_11th" <?php if ($std == 'art_11th') echo 'selected'; ?>>11th Arts</option>
            <option value="art_12th" <?php if ($std == 'art_12th') echo 'selected'; ?>>12th Arts</option>
            <option value="sci_11th" <?php if ($std == 'sci_11th') echo 'selected'; ?>>11th Science</option>
            <option value="sci_12th" <?php if ($std == 'sci_12th') echo 'selected'; ?>>12th Science</option>
            <option value="10th" <?php if ($std == '10th') echo 'selected'; ?>>10th</option>
        </select><br><br>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>"><br><br>

        <label for="roll_no">Roll Number:</label>
        <input type="text" id="roll_no" name="roll_no" value="<?php echo htmlspecialchars($roll_no); ?>"><br><br>

        <input type="submit" value="Search">
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($result)): ?>
        <h2>Results:</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Student ID</th>
                <th>Roll No</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["student_id"]; ?></td>
                        <td><?php echo $row["roll_no"]; ?></td>
                        <td><?php echo $row["date"]; ?></td>
                        <td><?php echo $row["status"]; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan='5'>No results found</td></tr>
            <?php endif; ?>
        </table>
    <?php endif; ?>

    <?php $conn->close(); ?>
    <button onclick="window.location.href='admin.php'" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 10px 0;">Back</button>

</body>
</html>
