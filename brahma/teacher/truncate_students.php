<?php
// Database connection
$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "udhayam_institute";
$conn = new mysqli($servername, $username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to archive and truncate attendance tables
function archiveAndTruncate($conn, $currentTable, $archiveTable) {
    // Copy data to archived table with roll numbers
    $copySql = "INSERT INTO $archiveTable (student_id, roll_no, date, status)
                SELECT a.student_id, s.roll_no, a.date, a.status
                FROM $currentTable a
                JOIN students s ON a.student_id = s.student_id";
                
    if ($conn->query($copySql) === TRUE) {
        echo "Data copied to $archiveTable successfully.<br>";
    } else {
        echo "Error copying data to $archiveTable: " . $conn->error . "<br>";
    }

    // Truncate the current table
    $truncateSql = "TRUNCATE TABLE $currentTable";
    if ($conn->query($truncateSql) === TRUE) {
        echo "Table $currentTable truncated successfully.<br>";
    } else {
        echo "Error truncating table $currentTable: " . $conn->error . "<br>";
    }
}

// Archive and truncate each attendance table
archiveAndTruncate($conn, "attend_art_11th", "archived_attend_art_11th");
archiveAndTruncate($conn, "attend_art_12th", "archived_attend_art_12th");
archiveAndTruncate($conn, "attend_sci_11th", "archived_attend_sci_11th");
archiveAndTruncate($conn, "attend_sci_12th", "archived_attend_sci_12th");
archiveAndTruncate($conn, "attend_10th", "archived_attend_10th");

// Start session for admin functionality
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_password = $_POST["admin_password"];

    // Fetch the admin password from the admin table
    $sql_fetch_password = "SELECT password FROM admin WHERE email = '" . $_SESSION['admin_email'] . "'";
    $result = $conn->query($sql_fetch_password);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $admin_password = $row['password'];

        if (password_verify($input_password, $admin_password)) { // Assuming the password is hashed

            // Archive all student records
            $sql_archive = "INSERT INTO archived_students (student_id, roll_no, student_name, date_of_birth, std, email, ph_no, name_of_grp, age, date_of_join, mat_cbsc, blood_grp, school_name, password, archived_at)
                            SELECT student_id, roll_no, student_name, date_of_birth, std, email, ph_no, name_of_grp, age, date_of_join, mat_cbsc, blood_grp, school_name, password, NOW()
                            FROM students";

            if ($conn->query($sql_archive) === TRUE) {
                // Disable foreign key checks
                $conn->query("SET FOREIGN_KEY_CHECKS = 0");

                // Truncate the students table
                $sql_truncate = "TRUNCATE TABLE students";

                if ($conn->query($sql_truncate) === TRUE) {
                    echo "All student records archived and deleted successfully!";
                } else {
                    echo "Error: " . $sql_truncate . "<br>" . $conn->error;
                }

                // Re-enable foreign key checks
                $conn->query("SET FOREIGN_KEY_CHECKS = 1");

                header("Location: student_list.php");
                exit();
            } else {
                echo "Error archiving student records: " . $conn->error;
            }
        } else {
            echo "Incorrect admin password. Please try again.";
        }
    } else {
        echo "Admin record not found.";
    }
}

$conn->close();
?>
