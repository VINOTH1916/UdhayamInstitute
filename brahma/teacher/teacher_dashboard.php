<?php
session_start();

if (!isset($_SESSION["teacher_name"]) || !isset($_SESSION["teacher_id"])) {
    header("Location: teacher_login.php");
    exit();
}

$teacher_name = $_SESSION["teacher_name"];
$teacher_id = $_SESSION["teacher_id"];

$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "udhayam_institute";

$conn = new mysqli($servername, $username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM teachers WHERE teacher_id = '$teacher_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $email = $row["email"];
        $date_of_join = $row["date_of_join"];
        $phone_number = $row["phone_number"];
    }
} else {
    echo "0 results found";
}

// Fetch students grouped by standard and group
$sql_students = "SELECT std, name_of_grp, roll_no, student_name FROM students ORDER BY std, name_of_grp, roll_no";
$result_students = $conn->query($sql_students);
$students_by_class = [];

if ($result_students->num_rows > 0) {
    while ($row = $result_students->fetch_assoc()) {
        $std = $row['std'];
        $name_of_grp = $row['name_of_grp'];
        $students_by_class[$std][$name_of_grp][] = ['roll_no' => $row['roll_no'], 'student_name' => $row['student_name']];
    }
} else {
    echo "No students found.";
}



$sql_classes_groups = "SELECT DISTINCT std, name_of_grp FROM students";
$result_classes_groups = $conn->query($sql_classes_groups);

$top_students_by_class_group = [];

if ($result_classes_groups->num_rows > 0) {
    while ($class_group = $result_classes_groups->fetch_assoc()) {
        $std = $class_group['std'];
        $name_of_grp = $class_group['name_of_grp'];

        // Fetch top 3 students for each class and group
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

        if ($result_top_students->num_rows > 0) {
            $top_students_by_class_group["$std - $name_of_grp"] = [];
            while ($top_student = $result_top_students->fetch_assoc()) {
                $percentage = ($top_student['total_marks_obtained'] / $top_student['total_marks_possible']) * 100;
                $top_students_by_class_group["$std - $name_of_grp"][] = [
                    'roll_no' => $top_student['roll_no'],
                    'student_name' => $top_student['student_name'],
                    'percentage' => round($percentage, 2)
                ];
            }
        }
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" type="text/css" href="teacher.css">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Cache-Control" content="post-check=0, pre-check=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 18px;
    }
    table, th, td {
        border: 1px solid #ccc;
    }
    th, td {
        padding: 10px;
        text-align: center;
    }
    th {
        background-color: #333;
        color: #fff;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    tr:hover {
        background-color: #ddd;
    }
    .class-group {
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 5px;
        background-color: #f9f9f9;
        margin: 20px 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-size: 30px;
    }

    .class-group h2 {
        font-size: 37px;
        color: #333;
        margin-bottom: 10px;
    }

    .class-link {
        font-size: 37px;
        color: #007BFF;
        cursor: pointer;
        margin: 10px 0;
        transition: color 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .class-link:hover {
        color: #0056b3;
    }

    .class-link + .student-list {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s ease, opacity 0.5s ease;
        opacity: 0;
        font-size: 30px;
    }

    .class-link.active + .student-list {
        max-height: 500px; /* Adjust as needed */
        opacity: 1;
    }

    .student-list {
        margin: 10px 0;
        padding: 0 20px;
        font-size: 20px;
    }

    .student-list ul {
        list-style: none;
        padding: 0;
        font-size: 20px;
    }

    .student-list ul li {
        font-size: 37px;
        color: #555;
        margin: 5px 0;
        font-size: 20px;
    }

    .toggle-button {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .class-link.active .toggle-button .arrow {
        transform: rotate(180deg);
    }

    .toggle-button {
        background: none;
        border: 1px solid #007BFF;
        border-radius: 5px;
        font-size: 20px;
        cursor: pointer;
        transition: transform 0.3s ease, background-color 0.3s ease;
        padding: 5px;
    }

    .toggle-button:hover {
        background-color: #e0f0ff;
    }

    .class-link.active .toggle-button .arrow {
        transform: rotate(180deg);
    }

    .profile-details {
        font-size: 40px;
    }

    @media (max-width: 600px) {
        table {
            font-size: 14px;
        }
        .class-group {
            font-size: 24px;
            padding: 15px;
        }
        .class-group h2 {
            font-size: 28px;
        }
        .class-link {
            font-size: 24px;
            flex-direction: column;
            align-items: flex-start;
        }
        .class-link .toggle-button {
            margin-top: 10px;
        }
        .student-list ul li {
            font-size: 18px;
        }
        .toggle-button {
            font-size: 16px;
            padding: 3px;
        }
        .profile-details {
            font-size: 20px;
        }
    }.circular-bars {
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

    <script>
       function toggleStudentList(std, group) {
    var classLink = document.querySelector(`h3.class-link[onclick="toggleStudentList('${std}', '${group}')"]`);
    var studentList = classLink.nextElementSibling;

    if (classLink.classList.contains('active')) {
        classLink.classList.remove('active');
    } else {
        classLink.classList.add('active');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const circles = document.querySelectorAll('.circle');

    circles.forEach(circle => {
        const percentage = parseFloat(circle.querySelector('.percentage').innerText);
        circle.style.setProperty('--percentage', percentage);
    });
});


    </script>
</head>

<body>
    <div class="header">
        <img src="logo.png" height="190px" width="auto" style="float: left; margin-right: 20px;" alt="Logo">
        <h1 style="color:green;">WELCOME TO UDHAYAM INSTITUTE</h1>
    </div>

    <div id="navbar">
        <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
        <div style="overflow: hidden;">
            <h1 style="text-align: right; margin: 0;"> Name: <?php echo $teacher_name; ?></h1>
            <h1 style="text-align: right; margin: 0;"> Teacher ID: <?php echo $teacher_id; ?></h1>
        </div>
    </div>

    <div id="myNav" class="overlay">
        <div class="overlay-content">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <ul>
                <li><a href="view_attendance.php">View Student Attendance</a></li>
                <li><a href="view_student.php">View Student Bio Data</a></li>
                <li><a href="attendance.php">Take Attendance</a></li>
                <li><a href="add_mark.html">Add Marks to Students</a></li>
                <li><a href="edit_marks.html">Edit Marks</a></li>
                <li><a href="teacher_profile.php">profile</a></li>
                <li><a href="teacher_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>

    

    <div class="profile-details">


    <h1>Top Students by Class and Group</h1>
    <div class="container">
        <?php foreach ($top_students_by_class_group as $class_group => $students): ?>
            <div class="class-group">
                <h2><?php echo $class_group; ?></h2>
                <div class="circular-bars">
                    <?php foreach ($students as $index => $student): ?>
                        <?php
                        $rank = $index + 1;
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
                        ?>
                        <div class='circular-bar <?php echo $colorClass; ?>'>
                            <div class='circle' style='--percentage: <?php echo $student['percentage']; ?>'>
                                <div class='mask full'>
                                    <div class='fill'></div>
                                </div>
                                <div class='mask half'>
                                    <div class='fill'></div>
                                </div>
                                <div class='inside-circle'>
                                    <span class='percentage'><?php echo $student['percentage']; ?>%</span>
                                    <span class='rank'>Rank <?php echo $rank; ?></span>
                                    <span class='name'><?php echo $student['student_name']; ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h1>Student List</h1>
    <?php foreach ($students_by_class as $std => $groups): ?>
        <div class="class-group">
            <h2>Class: <?php echo $std; ?></h2>
            <?php foreach ($groups as $group => $students): ?>
                <h3 class="class-link" onclick="toggleStudentList('<?php echo $std; ?>', '<?php echo $group; ?>')">
                <button style="background-color: #bebcbc; color: black; padding: 10px 20px; border: none; border-radius: 5px; font-size:25;">
    Group: <?php echo ucfirst($group); ?>
</button>
 
                    <button class="toggle-button">
                        <span class="arrow">▼</span>
                    </button>
                </h3>
                <div id="student-list-<?php echo $std; ?>-<?php echo $group; ?>" class="student-list">
                    <table>
                        <tr>
                            <th>Roll Number</th>
                            <th>Student Name</th>
                        </tr>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo $student['roll_no']; ?></td>
                                <td><?php echo $student['student_name']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>


    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>

    <script>
        function openNav() {
            document.getElementById("myNav").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("myNav").style.width = "0";
        }
    </script>

    <footer>
        <div class="footer-content">
            <div class="footer-left">
                <p>&copy; 2023 UDHAYAM INSTITUTE. All rights reserved.</p>
                <p>Contact: info@udhayaminstitute.com</p>
            </div>
            <div class="footer-right">
                <h3>திருக்குறள் அதிகாரம் 42 – கேள்வி</h3>
                <p>குறள் 412:<br> செவிக்குண வில்லாத போழ்து சிறிது <br> வயிற்றுக்கும் ஈயப் படும்</p>
            </div>
        </div>
    </footer>
</body>
</html>
