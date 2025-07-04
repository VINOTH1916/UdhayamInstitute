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

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" type="text/css" href="teacher.css">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Cache-Control" content="post-check=0, pre-check=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <style>
        /* Add your CSS styles here */
    </style>
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
                <li><a href="teacher_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>

    <div class="profile-details">
        <h1>Profile Details:</h1>
       
        <div>
            <h2>Email:</h2>
            <p><?php echo $email; ?></p>
        </div>
        <div>
            <h2>Date of Joining:</h2>
            <p><?php echo $date_of_join; ?></p>
        </div>
        
        <div>
            <h2>Phone Number:</h2>
            <p><?php echo $phone_number; ?></p>
        </div>
       
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
