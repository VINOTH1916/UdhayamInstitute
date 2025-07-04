<!DOCTYPE html>
<html>
<head>
    <title>Student Bio Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%; /* Set the width of the container */
    max-width: 1200px; /* Set the maximum width of the container */
    margin: 0 auto;
    padding-top: 20px;  /* Center the container horizontally */
    padding: 60px; /* Set inner spacing for content within the container */
    background-color: #f6f2f2; /* Set the background color of the container */
    border-radius: 20px; /* Set the border radius to create rounded corners */
    box-shadow: 0px 0px 10px 0px rgba(213, 9, 9, 0.1);
    position: relative;
    top: 70px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .student-info {
            padding: 20px;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Bio Data</h1>
            <!-- Create a search form -->
        <form method="POST">
            <label for="search_name">Search by Name:</label>
            <input type="text" id="search_name" name="search_name">

            <label for="search_roll_no">Search by Roll No:</label>
            <input type="text" id="search_roll_no" name="search_roll_no">

            <input type="submit" name="search" value="Search">
        </form>
        <?php
        session_start();

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["search"])) {
            // Database connection details
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "udhayam_institute";

            // Establish a database connection
            $conn = mysqli_connect($servername, $username, $password, $dbname);

            // Check the database connection
            if (!$conn) {
                die("Database connection failed: " . mysqli_connect_error());
            }

            $search_name = $_POST["search_name"];
            $search_roll_no = $_POST["search_roll_no"];

            // Create a prepared statement to fetch student bio data based on name or roll number
            $studentQuery = "SELECT * FROM students WHERE 1";

            if (!empty($search_name)) {
                $studentQuery .= " AND student_name LIKE ?";
            }

            if (!empty($search_roll_no)) {
                $studentQuery .= " AND roll_no = ?";
            }

            // Prepare the statement
            $stmt = mysqli_prepare($conn, $studentQuery);

            // Bind parameters and execute
            if (!empty($search_name) && !empty($search_roll_no)) {
                mysqli_stmt_bind_param($stmt, "si", $search_name, $search_roll_no);
            } elseif (!empty($search_name)) {
                mysqli_stmt_bind_param($stmt, "s", $search_name);
            } elseif (!empty($search_roll_no)) {
                mysqli_stmt_bind_param($stmt, "i", $search_roll_no);
            }

            // Execute the prepared statement
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Display the student bio data
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='student-info'>";
                echo "<p><span class='label'>Roll No:</span> " . $row["roll_no"] . "</p>";
                echo "<p><span class='label'>Student Name:</span> " . $row["student_name"] . "</p>";
                echo "<p><span class='label'>Date of Birth:</span> " . $row["date_of_birth"] . "</p>";
                echo "<p><span class='label'>Standard:</span> " . $row["std"] . "</p>";
                echo "<p><span class='label'>Email:</span> " . $row["email"] . "</p>";
                echo "<p><span class='label'>Phone Number:</span> " . $row["ph_no"] . "</p>";
                echo "<p><span class='label'>Name of Group:</span> " . $row["name_of_grp"] . "</p>";
                echo "<p><span class='label'>Age:</span> " . $row["age"] . "</p>";
                echo "<p><span class='label'>Date of Join:</span> " . $row["date_of_join"] . "</p>";
                echo "<p><span class='label'>Mat/CBSE:</span> " . $row["mat_cbsc"] . "</p>";
                echo "<p><span class='label'>Blood Group:</span> " . $row["blood_grp"] . "</p>";
                echo "<p><span class='label'>School Name:</span> " . $row["school_name"] . "</p>";
                echo "</div>";
            }

            // Close the prepared statement
            mysqli_stmt_close($stmt);

            // Close the database connection
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>
