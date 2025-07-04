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

function fetchStudents($std, $name_of_grp, $conn) {
    $sql = "SELECT roll_no, student_name FROM students WHERE std='$std' AND name_of_grp='$name_of_grp'";
    return $conn->query($sql);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $std = $_POST['std'];
    $name_of_grp = $_POST['name_of_grp'];
    $test_name = $_POST['test_name'];
    $subject_name = $_POST['subject_name'];
    $total_marks = $_POST['total_marks'];
    $date = $_POST['date'];

    $students = fetchStudents($std, $name_of_grp, $conn);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mark Sheet</title>
        <link rel="stylesheet" type="text/css" href="styles.css">

<style>body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
    background-color: #ffffff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: 30px;
    border-radius: 10px;
   height: auto;
}

h1, h2 {
    text-align: center;
    color: #333;
}

form {
    width: 100%;
    margin-top: 20px;
}

label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
    color: #333;
}

input[type="text"],
input[type="number"],
input[type="date"],
select {
    width: auto;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="submit"] {
    width: 100%;
    padding: 15px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

th {
    background-color: #4CAF50;
    color: white;
}

td {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

.preloader {
    display: none;
}
</style>

    </head>
    <body>
        <div class="container">
            <h2>Mark Sheet for <?php echo $test_name . " - " . $subject_name; ?></h2>
            <form action="submit_marks.php" method="POST">
                <input type="hidden" name="std" value="<?php echo $std; ?>">
                <input type="hidden" name="name_of_grp" value="<?php echo $name_of_grp; ?>">
                <input type="hidden" name="test_name" value="<?php echo $test_name; ?>">
                <input type="hidden" name="subject_name" value="<?php echo $subject_name; ?>">
                <input type="hidden" name="total_marks" value="<?php echo $total_marks; ?>">
                <input type="hidden" name="date" value="<?php echo $date; ?>">
                
                <table>
                    <tr>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th>Marks (out of <?php echo $total_marks; ?>)</th>
                    </tr>
                    <?php
                    if ($students->num_rows > 0) {
                        while($row = $students->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['roll_no'] . "</td>";
                            echo "<td>" . $row['student_name'] . "</td>";
                            echo "<td><input type='number' name='marks[" . $row['roll_no'] . "]' max='" . $total_marks . "' required></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No students found</td></tr>";
                    }
                    ?>
                </table>
                <input type="submit" value="Submit Marks">
            </form>
        </div>
    </body>
    </html>

    <?php
}

$conn->close();
?>
