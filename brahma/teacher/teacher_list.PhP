<!DOCTYPE html>
<html>
<head>
    <title>Teacher List</title>
    <link rel="stylesheet" type="text/css" href="view_attendance.css">
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
        }

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

        .edit-button {
            background-color: #4CAF50;
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

        .edit-button:hover {
            background-color: #45a049;
        }

        .delete-button {
            background-color: red;
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

        .delete-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
<button onclick="window.location.href='admin.php'" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 10px 0;">Home</button>
    <h1>Teacher List</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>

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

        // Fetch the teacher data
        $sql = "SELECT teacher_id, name, email FROM teachers";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['teacher_id'] . '</td>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td><a href="edit_teacher.php?id=' . $row['teacher_id'] . '" class="edit-button">Edit</a></td>';
                echo '<td><a href="delete_teacher.php?id=' . $row['teacher_id'] . '" class="delete-button" onclick="return confirm(\'Are you sure you want to delete this teacher?\')">Delete</a></td>';
                echo '</tr>';
            }
        }
        
         else {
            echo '<tr><td colspan="4">No teachers found.</td></tr>';
        }

        $conn->close();
        ?>

    </table>
</div>

</body>
</html>
