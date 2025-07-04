<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "udhayam_institute";
$conn = new mysqli($servername, $username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate the token
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $result = $conn->query("SELECT * FROM student_tokens WHERE token='$token' AND expiry_date > NOW()");

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $student_id = $row['student_id'];
        // Token is valid, display form to complete profile
        
        echo '<link rel="stylesheet" type="text/css" href="formcss.css">';
        
        echo '
        <h1>Welcome to UDHAYAM INSTITUTE! complete your registration</h1>
            <div class="container">
                <div id="preloader" class="preloader">
                    <video autoplay loop muted height="200px" width="auto">
                        <source src="loading-video.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <form action="complete_registration.php" method="POST">
                    <input type="hidden" name="student_id" value="' . $student_id . '">
                    <label for="date_of_birth">Date of Birth:</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" required onchange="calculateAge()">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" required readonly>
                    <label for="date_of_join">Date of Join:</label>
                    <input type="date" id="date_of_join" name="date_of_join" required>
                    <label for="mat_cbsc">Matriculation/CBSE:</label>
                    <select id="mat_cbsc" name="mat_cbsc" required>
                        <option value="mat">Matriculation</option>
                        <option value="cbsc">CBSE</option>
                    </select>
                    <label for="blood_grp">Blood Group:</label>
                    <select id="blood_grp" name="blood_grp" required>
                        <option value="" disabled selected>Select your blood group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                    <label for="school_name">School Name:</label>
                    <input type="text" id="school_name" name="school_name" required>
                    <input type="submit" value="Complete Registration">
                </form>
            </div>
            <script>
                function calculateAge() {
                    var dob = document.getElementById("date_of_birth").value;
                    var dobDate = new Date(dob);
                    var today = new Date();
                    var age = today.getFullYear() - dobDate.getFullYear();
                    var monthDifference = today.getMonth() - dobDate.getMonth();
        
                    // If the birth date has not occurred yet this year, adjust the age
                    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dobDate.getDate())) {
                        age--;
                    }
        
                    document.getElementById("age").value = age;
                }
            </script>
        ';
        
        
        
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}

$conn->close();
?>
