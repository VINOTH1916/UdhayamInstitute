<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "udhayam_institute";

// Establish a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $selectedClass = $_POST["class"];

    // Construct SQL query to retrieve student data for the selected class
    $sql = "SELECT * FROM students WHERE std = ?";

    // Prepare and bind the SQL query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedClass);

    // Execute the query
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Display student data in a table
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Roll No</th><th>Student Name</th><th>Date of Birth</th><th>Standard</th><th>Email</th><th>Phone Number</th><th>Name of Group</th><th>Age</th><th>Date of Join</th><th>Mat/CBSE</th><th>Blood Group</th><th>School Name</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["roll_no"] . "</td>";
            echo "<td>" . $row["student_name"] . "</td>";
            echo "<td>" . $row["date_of_birth"] . "</td>";
            echo "<td>" . $row["std"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["ph_no"] . "</td>";
            echo "<td>" . $row["name_of_grp"] . "</td>";
            echo "<td>" . $row["age"] . "</td>";
            echo "<td>" . $row["date_of_join"] . "</td>";
            echo "<td>" . $row["mat_cbsc"] . "</td>";
            echo "<td>" . $row["blood_grp"] . "</td>";
            echo "<td>" . $row["school_name"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No students found for the selected class.";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Data</title>
</head>
<body>
    <h1>Student Data by Class</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="class">Select Class:</label>
        <select name="class" id="class">
            <!-- You can dynamically populate this dropdown from your database if needed -->
            <option value="10">10th</option>
            <option value="11">11th</option>
            <option value="12">12th</option>
            <!-- Add more options as needed -->
        </select>
        <input type="submit" name="submit" value="Show Students">
    </form>

    <br>
    <a href="admin.php">Go Back to Home</a>
</body>
</html>
