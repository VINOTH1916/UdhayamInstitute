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

    // Fetch the student record
    $sql_fetch = "SELECT * FROM students WHERE student_id='$student_id'";
    $result = $conn->query($sql_fetch);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Insert the student record into archived_students
        $sql_archive = "INSERT INTO archived_students (student_id, roll_no, student_name, date_of_birth, std, email, ph_no, name_of_grp, age, date_of_join, mat_cbsc, blood_grp, school_name, password)
                        VALUES ('" . $row['student_id'] . "', '" . $row['roll_no'] . "', '" . $row['student_name'] . "', '" . $row['date_of_birth'] . "', '" . $row['std'] . "', '" . $row['email'] . "', '" . $row['ph_no'] . "', '" . $row['name_of_grp'] . "', '" . $row['age'] . "', '" . $row['date_of_join'] . "', '" . $row['mat_cbsc'] . "', '" . $row['blood_grp'] . "', '" . $row['school_name'] . "', '" . $row['password'] . "')";

        if ($conn->query($sql_archive) === TRUE) {
            // Delete the student token first to avoid foreign key constraint violation
            $sql_delete_token = "DELETE FROM student_tokens WHERE student_id='$student_id'";
            if ($conn->query($sql_delete_token) === TRUE) {
                // Delete the student record from students table
                $sql_delete = "DELETE FROM students WHERE student_id='$student_id'";
                if ($conn->query($sql_delete) === TRUE) {
                    echo "Student record and token deleted and archived successfully!";
                } else {
                    echo "Error deleting student record: " . $conn->error;
                }
            } else {
                echo "Error deleting student token: " . $conn->error;
            }
        } else {
            echo "Error archiving student record: " . $conn->error;
        }
    } else {
        echo "Student record not found.";
    }

    header("Location: student_list.php");
    exit();
}

$conn->close();
?>
