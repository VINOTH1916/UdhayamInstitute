<?php
session_start();

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
    $input_password = $_POST["admin_password"];

    // Fetch the admin's hashed password from the database
    $admin_email = $_SESSION['admin_email'];
    $sql = "SELECT password FROM admin WHERE email='$admin_email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify the input password against the hashed password
        if (password_verify($input_password, $hashed_password)) {
            // Delete all records from archived_students
            $sql_delete = "TRUNCATE TABLE archived_students";

            if ($conn->query($sql_delete) === TRUE) {
                echo "All archived student records deleted successfully!";
            } else {
                echo "Error: " . $sql_delete . "<br>" . $conn->error;
            }

            header("Location: archived_student_list.php");
            exit();
        } else {
            echo "Incorrect admin password. Please try again.";
        }
    } else {
        echo "Admin not found!";
    }
}

$conn->close();
?>
