<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['roll_no']) || !isset($_SESSION['student_name'])) {
    header("Location: login.php");
    exit();
}

$roll_no = $_SESSION['roll_no'];
$student_name = $_SESSION['student_name'];

// Extract standard and group code from roll number
$std_code = substr($roll_no, 4, 2);
$group_code = substr($roll_no, 6, 2);

$std = '';
$name_of_grp = '';

if ($std_code == "10") {
    $std = "10";
    $name_of_grp = "none";
} elseif ($std_code == "11" && $group_code == "05") {
    $std = "11";
    $name_of_grp = "art";
} elseif ($std_code == "11" && $group_code == "15") {
    $std = "11";
    $name_of_grp = "sci";
} elseif ($std_code == "12" && $group_code == "10") {
    $std = "12";
    $name_of_grp = "art";
} elseif ($std_code == "12" && $group_code == "20") {
    $std = "12";
    $name_of_grp = "sci";
} else {
    echo "Invalid roll number.";
    exit();
}

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "udhayam_institute";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the latest test information
$sql_latest_test = "SELECT test_name, subject_name, total_marks, date 
                    FROM marks 
                    WHERE std='$std' AND name_of_grp='$name_of_grp' 
                    ORDER BY date DESC LIMIT 1";
$result_latest_test = $conn->query($sql_latest_test);
$latest_test_info = $result_latest_test->fetch_assoc();

// Fetch all students in the same class and group
$sql_students = "SELECT roll_no, student_name FROM students WHERE std='$std' AND name_of_grp='$name_of_grp'";
$result_students = $conn->query($sql_students);

// Fetch top 3 students based on latest test marks
$sql_top_students = "SELECT m.roll_no, s.student_name, m.marks 
                     FROM marks m
                     INNER JOIN students s ON m.roll_no = s.roll_no 
                     WHERE m.std='$std' AND m.name_of_grp='$name_of_grp' 
                     ORDER BY m.marks DESC LIMIT 3";
$result_top_students = $conn->query($sql_top_students);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marks</title>
    <link rel="stylesheet" href="mark.css">
</head>

<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f0f0;
}

.navbar {
    overflow: hidden;
    background-color: white;
    color: rgb(15, 2, 2);
    padding: 14px 20px;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
}

.navbar span {
    font-size: 30px;
    cursor: pointer;
}

.navbar .user-info {
    float: right;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.user-info h2 {
    margin: 0;
    padding: 0;
}

.overlay {
    height: 100%;
    width: 0;
    position: fixed;
    z-index: 1;
    top: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.9);
    overflow-x: hidden;
    transition: 0.5s;
}

.overlay-content {
    position: relative;
    top: 25%;
    width: 100%;
    text-align: center;
    margin-top: 30px;
}

.overlay a {
    padding: 8px;
    text-decoration: none;
    font-size: 36px;
    color: #818181;
    display: block;
    transition: 0.3s;
}

.overlay a:hover, .overlay a:focus {
    color: #f1f1f1;
}

.overlay .closebtn {
    position: absolute;
    top: -60px;
    right: 90px;
    font-size: 60px;
    cursor: pointer;
}

.profile-details {
    padding: 80px 20px 20px 20px;
    background: white;
    margin: 20px auto;
    max-width: 800px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.profile-details h2 {
    color: #333;
    border-bottom: 2px solid #4CAF50;
    padding-bottom: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 16px;
}

table, th, td {
    border: 1px solid #ccc;
}

th, td {
    padding: 10px;
    text-align: center;
}

th {
    background-color: #d7d4d4;
    color: rgb(12, 12, 12);
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #ddd;
}

.test-info {
    margin: 20px 0;
}

.forgot-password {
    display: block;
    margin: 20px 0;
    color: #4CAF50;
    text-align: right;
}

.forgot-password:hover {
    text-decoration: underline;
}

.fade-in {
    animation: fadeIn 1s ease-in-out;
}


.circular-bars {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
}

.circular-bar {
    position: relative;
    width: 100px;
    height: 100px;
}

.circle {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: conic-gradient(#ccc 0% 50%, transparent 50% 100%);
    color: #ddd;
}

.mask, .fill {
    width: 100%;
    height: 100%;
    position: absolute;
    border-radius: 50%;
}

.mask {
    clip: rect(0px, 150px, 150px, 75px);
}

.mask.full, .fill {
    animation: fillEase 2s ease-in-out forwards;
}

.mask.half {
    clip: rect(0px, 75px, 150px, 0px);
}

.green .fill {
    background-color: green;
}

.red .fill {
    background-color: red;
}

.blue .fill {
    background-color: blue;
}

.inside-circle {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.percentage {
    font-size: 24px;
    font-weight: bold;
}

.rank {
    display: block;
    font-size: 16px;
    margin-top: 5px;
}

.name {
    display: block;
    font-size: 14px;
    margin-top: 5px;
}

@keyframes fillEase {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(calc(3.6 * var(--percentage)));
    }
}


@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.slide-in {
    animation: slideIn 0.5s forwards;
}

@keyframes slideIn {
    from {
        width: 0;
    }
    to {
        width: 250px;
    }
}

.profile-details h2 {
    position: relative;
    animation: slideInFromLeft 1s ease-out;
}

@keyframes slideInFromLeft {
    from {
        left: -100px;
        opacity: 0;
    }
    to {
        left: 0;
        opacity: 1;
    }
}

</style>
<body>

<div class="navbar">


    <span onclick="openNav()">&#9776;</span>
   
   
</div>

<div id="myNav" class="overlay">

    <div class="overlay-content">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="checkattendance.php">Attendance</a>
        <a href="dashboard.php">HOME</a>
        <a href="logout.php">Logout</a>
        
    </div>
   
</div>

<div class="profile-details">
<div class="user-info">
    <h2><?php echo $student_name; ?></h>
    <h2><?php echo $roll_no; ?></h2>
    </div>
    <h2>Top 3 Students</h2>
<div class="circular-bars">
    <?php
    $sql_top_students = "SELECT s.roll_no, s.student_name, 
                         SUM(m.marks) AS total_marks_obtained, 
                         SUM(m.total_marks) AS total_marks_possible 
                         FROM marks m
                         INNER JOIN students s ON m.roll_no = s.roll_no 
                         WHERE m.std='$std' AND m.name_of_grp='$name_of_grp' 
                         GROUP BY s.roll_no, s.student_name 
                         ORDER BY SUM(m.marks) / SUM(m.total_marks) DESC 
                         LIMIT 3";

    $result_top_students = $conn->query($sql_top_students);

    $rank = 1;
    while ($top_student = $result_top_students->fetch_assoc()) {
        $percentage = ($top_student['total_marks_obtained'] / $top_student['total_marks_possible']) * 100;
        $colorClass = '';
        switch ($rank) {
            case 1:
                $colorClass = 'green';
                break;
            case 2:
                $colorClass = 'red';
                break;
            case 3:
                $colorClass = 'blue';
                break;
        }
        echo "<div class='circular-bar $colorClass'>
                <div class='circle' style='--percentage: $percentage'>
                    <div class='mask full'>
                        <div class='fill'></div>
                    </div>
                    <div class='mask half'>
                        <div class='fill'></div>
                    </div>
                    <div class='inside-circle'>
                        <span class='percentage'>" . round($percentage, 2) . "%</span>
                        <span class='rank'>Rank $rank</span>
                        <span class='name'>{$top_student['student_name']}</span>
                    </div>
                </div>
              </div>";
        $rank++;
    }
    ?>
</div>


    <?php if ($latest_test_info): ?>
        <div class="test-info">
            <p><strong>Latest Test Name:</strong> <?php echo $latest_test_info['test_name']; ?></p>
            <p><strong>Subject Name:</strong> <?php echo $latest_test_info['subject_name']; ?></p>
            <p><strong>Total Marks:</strong> <?php echo $latest_test_info['total_marks']; ?></p>
            <p><strong>Date:</strong> <?php echo $latest_test_info['date']; ?></p>
        </div>
    <?php else: ?>
        <p>No test information available.</p>
    <?php endif; ?>

    <h2>Class Marks</h2>
    <table>
        <tr>
            <th>Roll Number</th>
            <th>Student Name</th>
            <th>Marks</th>
        </tr>
        <?php
        while ($student = $result_students->fetch_assoc()) {
            $student_roll_no = $student['roll_no'];

            // Fetch the latest test marks for each student
            $sql_marks = "SELECT marks 
                          FROM marks 
                          WHERE roll_no='$student_roll_no' AND test_name='{$latest_test_info['test_name']}' 
                          ORDER BY date DESC LIMIT 1";
            $result_marks = $conn->query($sql_marks);

            if ($result_marks->num_rows > 0) {
                $row_marks = $result_marks->fetch_assoc();
                ?>
                <tr>
                    <td><?php echo $student['roll_no']; ?></td>
                    <td><?php echo $student['student_name']; ?></td>
                    <td><?php echo $row_marks['marks']; ?></td>
                </tr>
                <?php
            } else {
                ?>
                <tr>
                    <td><?php echo $student['roll_no']; ?></td>
                    <td><?php echo $student['student_name']; ?></td>
                    <td>No marks available</td>
                </tr>
                <?php
            }
        }
        ?>
    </table>

    <h2>All Tests and Marks</h2>
    <?php
    // Fetch all marks for each student
    $sql_all_marks = "SELECT subject_name, test_name, marks, total_marks
                      FROM marks 
                      WHERE roll_no='$roll_no'";
    $result_all_marks = $conn->query($sql_all_marks);

    $total_marks_obtained = 0;
    $total_marks_possible = 0;

    if ($result_all_marks->num_rows > 0) {
        while ($row_marks = $result_all_marks->fetch_assoc()) {
            $total_marks_obtained += $row_marks['marks'];
            $total_marks_possible += $row_marks['total_marks'];
        }
    }

    if ($total_marks_possible > 0) {
        $overall_percentage = ($total_marks_obtained / $total_marks_possible) * 100;
    } else {
        $overall_percentage = 0;
    }

    $overall_color = "black";
    if ($overall_percentage == 100) {
        $overall_color = "green";
    } elseif ($overall_percentage >= 90 && $overall_percentage < 100) {
        $overall_color = "yellow";
    } elseif ($overall_percentage >= 75 && $overall_percentage < 90) {
        $overall_color = "blue";
    }

    echo "<div style='font-size:20px; color:{$overall_color};'>
            Overall Percentage: " . round($overall_percentage, 2) . "%
          </div>";
    ?>

    <table>
        <tr>
            <th>Subject Name</th>
            <th>Test Name</th>
            <th>Marks</th>
            <th>Total Marks</th>
            <th>Percentage</th>
        </tr>
        <?php
        $result_all_marks->data_seek(0);
        if ($result_all_marks->num_rows > 0) {
            while ($row_marks = $result_all_marks->fetch_assoc()) {
                $percentage = ($row_marks['marks'] / $row_marks['total_marks']) * 100;
                
                $color = "black";
                if ($percentage == 100) {
                    $color = "green";
                } elseif ($percentage >= 90 && $percentage < 100) {
                    $color = "yellow";
                } elseif ($percentage >= 75 && $percentage < 90) {
                    $color = "blue";
                }

                echo "<tr>
                        <td>{$row_marks['subject_name']}</td>
                        <td>{$row_marks['test_name']}</td>
                        <td>{$row_marks['marks']}</td>
                        <td>{$row_marks['total_marks']}</td>
                        <td style='color:{$color};'>" . round($percentage, 2) . "%</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No tests available</td></tr>";
        }
        ?>
    </table>
</div>

<script>


document.addEventListener('DOMContentLoaded', () => {
    const circles = document.querySelectorAll('.circle');

    circles.forEach(circle => {
        const percentage = parseFloat(circle.querySelector('.percentage').innerText);
        circle.style.setProperty('--percentage', percentage);
    });
});


    function openNav() {
        document.getElementById("myNav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("myNav").style.width = "0";
    }
</script>

</body>
</html>
