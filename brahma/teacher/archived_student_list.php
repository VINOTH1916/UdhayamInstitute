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

// Fetch archived student data
$sql = "SELECT * FROM archived_students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Archived Students</title>
    <link rel="stylesheet" type="text/css" href="view_attendance.css">

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .container {
            width: 10000px;
            margin: 0 auto;
        }

        button {
            background-color: black;
            color: white;
            border: none;
            padding: 7px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            
        }


        .delete-all-button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
        }
        .delete-all-button {
            margin: 10px 0;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
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

// Initialize search variables
$year = '';
$std = '';
$name = '';
$dob = '';

// Capture search inputs
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $year = $_GET['year'] ?? '';
    $std = $_GET['std'] ?? '';
    $name = $_GET['name'] ?? '';
    $dob = $_GET['dob'] ?? '';
}

// Build the SQL query with search filters
$sql = "SELECT * FROM archived_students WHERE 1=1";

if (!empty($year)) {
    $sql .= " AND YEAR(date_of_join) = '$year'";
}
if (!empty($std)) {
    $sql .= " AND std = '$std'";
}
if (!empty($name)) {
    $sql .= " AND student_name LIKE '%$name%'";
}
if (!empty($dob)) {
    $sql .= " AND date_of_birth = '$dob'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Archived Students</title>
    <link rel="stylesheet" type="text/css" href="view_attendance.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .delete-all-button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
        }
        .delete-all-button {
            margin: 10px 0;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .search-form {
            margin-bottom: 20px;
        }
        .search-form input, .search-form select {
            padding: 5px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="container">
<button onclick="window.location.href='admin2.php'" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 10px 0;">Home</button>


    <h1>Archived Students</h1>
    <form method="GET" class="search-form">
        <label for="year">Year:</label>
        <input type="text" id="year" name="year" value="<?php echo htmlspecialchars($year); ?>">
        <label for="std">Standard:</label>
        <input type="text" id="std" name="std" value="<?php echo htmlspecialchars($std); ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
        <input type="submit" value="Search">
    </form>
    <button id="delete-all-button" class="delete-all-button">Delete All</button>
    <table>
        <thead>
            <tr>
                <th>S.NO</th>
                <th>Roll No</th>
                <th>Student Name</th>
                <th>Date of Birth</th>
                <th>Standard</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Group Name</th>
                <th>Age</th>
                <th>Date of Join</th>
                <th>Mat/CBSC</th>
                <th>Blood Group</th>
                <th>School Name</th>
                <th>Archived At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['archived_id'] . '</td>';
                    echo '<td>' . $row['roll_no'] . '</td>';
                    echo '<td>' . $row['student_name'] . '</td>';
                    echo '<td>' . $row['date_of_birth'] . '</td>';
                    echo '<td>' . $row['std'] . '</td>';
                    echo '<td>' . $row['email'] . '</td>';
                    echo '<td>' . $row['ph_no'] . '</td>';
                    echo '<td>' . $row['name_of_grp'] . '</td>';
                    echo '<td>' . $row['age'] . '</td>';
                    echo '<td>' . $row['date_of_join'] . '</td>';
                    echo '<td>' . $row['mat_cbsc'] . '</td>';
                    echo '<td>' . $row['blood_grp'] . '</td>';
                    echo '<td>' . $row['school_name'] . '</td>';
                    echo '<td>' . $row['archived_at'] . '</td>';
                    echo '<td>';
                    echo '<form action="delete_archived_student.php" method="POST" class="delete-button">';
                    echo '<input type="hidden" name="student_id" value="' . $row['student_id'] . '">';
                    echo '<input type="submit" value="Delete" class="delete-button" onclick="return confirm(\'Are you sure you want to delete this record?\');">';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="15">No archived students found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Admin Password Required</h2>
        <form action="delete_all_archived.php" method="POST">
            <label for="admin_password">Password:</label>
            <input type="password" id="admin_password" name="admin_password" required>
            <input type="submit" value="Confirm Delete" class="delete-button">
        </form>
    </div>
</div>

<script>
document.getElementById('delete-all-button').onclick = function() {
    document.getElementById('myModal').style.display = "block";
}

document.querySelector('.close').onclick = function() {
    document.getElementById('myModal').style.display = "none";
}

window.onclick = function(event) {
    if (event.target == document.getElementById('myModal')) {
        document.getElementById('myModal').style.display = "none";
    }
}
</script>

</body>
</html>

<?php
$conn->close();
?>

</html>

