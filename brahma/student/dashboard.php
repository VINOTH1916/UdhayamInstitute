<?php
session_start();

if (!isset($_SESSION["student_name"]) || !isset($_SESSION["roll_no"])) {
    header("Location: login.php");
    exit();
}

$student_name = $_SESSION["student_name"];
$roll_no = $_SESSION["roll_no"];


$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "udhayam_institute";

$conn = new mysqli($servername, $username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$sql = "SELECT * FROM students WHERE roll_no = '$roll_no'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $date_of_birth = $row["date_of_birth"];
        $std = $row["std"];
        $email = $row["email"];
        $date_of_join = $row["date_of_join"];
        $name_of_grp = $row["name_of_grp"];
        $mat_cbsc = $row["mat_cbsc"];
        $blood_grp = $row["blood_grp"];
        $school_name = $row["school_name"];
        $password = $row["password"];
    }
} else {
    echo "0 results found";
}

$conn->close();
?>


<!DOCTYPE html>
<html>

<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" type="text/css" href="vishnu.css">
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
        <div style="overflow: hidden;"> <!-- Create a container to clear the float -->
            <h1 style="text-align: right; margin: 0;"> Name:<?php echo $student_name; ?></h1>
            <h1 style="text-align: right; margin: 0;"> Roll No:<?php echo $roll_no; ?></h1>
        </div>
    </div>

    <div id="myNav" class="overlay">
        <div class="overlay-content">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <ul>
               
                <li><a href="checkattendance.php">Check My Attendance</a></li>
                <li><a href="view_marks.php">My Marks and Tests</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>

   



    
    <div class="profile-details">
    <h1>Profile Details:</h1>
    <div>
        <h2>Date of Birth:</h2>
        <p><?php echo $date_of_birth; ?></p>
    </div>
    <div>
        <h2>Standard:</h2>
        <p><?php echo $std; ?></p>
    </div>
    <div>
        <h2>Email:</h2>
        <p><?php echo $email; ?></p>
    </div>
    <div>
        <h2>Date of Joining:</h2>
        <p><?php echo $date_of_join; ?></p>
    </div>
    <div>
        <h2>Group Name:</h2>
        <p><?php echo $name_of_grp; ?></p>
    </div>
    <div>
        <h2>Matriculation/CBSC:</h2>
        <p><?php echo $mat_cbsc; ?></p>
    </div>
    <div>
        <h2>Blood Group:</h2>
        <p><?php echo $blood_grp; ?></p>
    </div>
    <div>
        <h2>School Name:</h2>
        <p><?php echo $school_name; ?></p>
    </div>
    <p><a href="forgotpassword.php" class="forgot-password">Forgot Password?</a></p>

    </div>

    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>

    

    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to log out?")) {
                document.getElementById('logout-form').submit();
            }
        }
    </script>

    <script>
        function openNav() {
            document.getElementById("myNav").style.width = "250px"; // Adjust the width of the sidebar as needed
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
