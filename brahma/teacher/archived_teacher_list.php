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

// Fetch archived teacher data
$sql = "SELECT * FROM archived_teachers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Archived Teachers</title>
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
    </style>
</head>
<body>
<div class="container">
    <h1>Archived Teachers</h1>
    <button onclick="window.location.href='admin.php'" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 10px 0;">Home</button>
    <button id="delete-all-button" class="delete-all-button">Delete All</button>
    <table>
        <thead>
            <tr>
                <th>Teacher ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Date of Joining</th>
                <th>Archived At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['teacher_id'] . '</td>';
                    echo '<td>' . $row['name'] . '</td>';
                    echo '<td>' . $row['email'] . '</td>';
                    echo '<td>' . $row['phone_number'] . '</td>';
                    echo '<td>' . $row['date_of_join'] . '</td>';
                    echo '<td>' . $row['archived_at'] . '</td>';
                    echo '<td>';
                    echo '<form action="delete_archived_teacher.php" method="POST" class="delete-button" ">';
                    echo '<input type="hidden" name="teacher_id" value="' . $row['teacher_id'] . '">';
                    echo '<input type="submit" value="Delete" class="delete-button" onclick="return confirm(\'Are you sure you want to delete this record?\');">';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="7">No archived teachers found.</td></tr>';
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
        <form action="delete_all_archived_teachers.php" method="POST">
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
