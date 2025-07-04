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

if (isset($_GET['id'])) {
    $teacher_id = $_GET['id'];

    // Archive the teacher data before deletion
    $archive_sql = "INSERT INTO archived_teachers (teacher_id, name, email, phone_number, date_of_join, password)
                    SELECT teacher_id, name, email, phone_number, date_of_join, password FROM teachers WHERE teacher_id='$teacher_id'";

    if ($conn->query($archive_sql) === TRUE) {
        // Delete the teacher from the teachers table
        $delete_sql = "DELETE FROM teachers WHERE teacher_id='$teacher_id'";
        
        if ($conn->query($delete_sql) === TRUE) {
            echo "Teacher deleted successfully!";
            header("Location: teacher_list.php");
            exit();
        } else {
            echo "Error deleting teacher: " . $conn->error;
        }
    } else {
        echo "Error archiving teacher: " . $conn->error;
    }
}

$conn->close();
?>
