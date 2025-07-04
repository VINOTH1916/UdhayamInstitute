<?php
session_start();

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "udhayam_institute";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetchMarks($std, $name_of_grp, $test_name, $subject_name, $conn) {
    $sql = "SELECT roll_no, student_name, marks, total_marks, date 
            FROM marks 
            NATURAL JOIN students
            WHERE std='$std' AND name_of_grp='$name_of_grp' AND test_name='$test_name' AND subject_name='$subject_name'";
    return $conn->query($sql);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $std = $_POST['std'];
    $name_of_grp = $_POST['name_of_grp'];
    $test_name = $_POST['test_name'];
    $subject_name = $_POST['subject_name'];

    $marks = fetchMarks($std, $name_of_grp, $test_name, $subject_name, $conn);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Marks</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <div class="container">
            <h2>Edit Marks for <?php echo $test_name . " - " . $subject_name; ?></h2>
            <form action="update_marks.php" method="POST">
                <input type="hidden" name="std" value="<?php echo $std; ?>">
                <input type="hidden" name="name_of_grp" value="<?php echo $name_of_grp; ?>">
                <input type="hidden" name="test_name" value="<?php echo $test_name; ?>">
                <input type="hidden" name="subject_name" value="<?php echo $subject_name; ?>">
                
                <table>
                    <tr>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th>Marks</th>
                        <th>Total Marks</th>
                        <th>Date</th>
                    </tr>
                    <?php
                    if ($marks->num_rows > 0) {
                        while($row = $marks->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['roll_no'] . "</td>";
                            echo "<td>" . $row['student_name'] . "</td>";
                            echo "<td><input type='number' name='marks[" . $row['roll_no'] . "]' value='" . $row['marks'] . "' max='" . $row['total_marks'] . "' required></td>";
                            echo "<td><input type='number' name='total_marks[" . $row['roll_no'] . "]' value='" . $row['total_marks'] . "' required></td>";
                            echo "<td><input type='date' name='date[" . $row['roll_no'] . "]' value='" . $row['date'] . "' required></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No marks found</td></tr>";
                    }
                    ?>
                </table>
                <input type="submit" value="Update Marks">
            </form>
        </div>
    </body>
    </html>

    <?php
}

$conn->close();
?>
