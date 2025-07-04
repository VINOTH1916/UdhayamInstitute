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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $student_name = $_POST["student_name"];
    $date_of_birth = $_POST["date_of_birth"];
    $age = $_POST["age"];
    $date_of_join = $_POST["date_of_join"];
    $mat_cbsc = $_POST["mat_cbsc"];
    $blood_grp = $_POST["blood_grp"];
    $school_name = $_POST["school_name"];
    $email = $_POST["email"];
    $ph_no = $_POST["ph_no"];
    $fees = $_POST["fees"];
    $password = $_POST["password"];

    // Update student profile
    $sql = "UPDATE students SET 
            student_name='$student_name',
            date_of_birth='$date_of_birth',
            age='$age',
            date_of_join='$date_of_join',
            mat_cbsc='$mat_cbsc',
            blood_grp='$blood_grp',
            school_name='$school_name',
            email='$email',
            ph_no='$ph_no',
            fees='$fees',
            password='$password'
            WHERE student_id='$student_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Student information updated successfully!";
        header("Location: student_list.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
