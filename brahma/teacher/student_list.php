
<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>
    <link rel="stylesheet" type="text/css" href="view_attendance.css">
    <style>
        .admin-header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 150px;
        }

        .admin-header h1 {
            font-size: 50px;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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

        h2 {
            margin-top: 40px;
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

        .edit-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
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
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        .delete-button:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>

<div class="admin-header">
    <h1 style="border: 2px solid black;background-color:white;color:green;border-radius: 100px;">UDHAYAM INSTITUTE</h1>
    <h1>Welcome to the Admin Panel</h1>
</div>

<div class="container">

<button onclick="window.location.href='admin.php'" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 10px 0;">Back</button>





<div style="top:100px">

</div>

    <h1>Student List</h1>
   
    

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

    // Fetch the student data
    $sql = "SELECT student_id, roll_no, student_name, std, name_of_grp ,fees FROM students ORDER BY std, name_of_grp, roll_no";
    $result = $conn->query($sql);
   
    if ($result->num_rows > 0) {
        $currentClassGroup = '';
        $classes = [
            '10' => '10th Class',
            '11art' => '11th Arts',
            '11sci' => '11th Science',
            '12art' => '12th Arts',
            '12sci' => '12th Science'
        ];

        while($row = $result->fetch_assoc()) {
            $classGroup = $row['std'];
            if ($row['std'] == '11' || $row['std'] == '12') {
                $classGroup .= $row['name_of_grp'];
            }

            // Check if we are starting a new class/group
            if ($currentClassGroup != $classGroup) {
                if ($currentClassGroup != '') {

                    
                    // Close the previous table if it exists
                    echo '</table>';
                }
                // Update the current class/group and start a new table
                $currentClassGroup = $classGroup;
                $classGroupName = $classes[$currentClassGroup];
                echo "<h2>$classGroupName</h2>";
                echo '<table>';
                echo '<tr><th>Roll Number</th><th>Student Name</th><th>Fees</th><th>Edit</th><th>Delete</th></tr>';
            }
            // Display the student row with edit button
            echo '<tr>';
            echo '<td>' . $row['roll_no'] . '</td>';
            echo '<td>' . $row['student_name'] . '</td>';
            echo '<td>' . $row['fees'] . '</td>';
            echo '<td><a href="edit_student.php?id=' . $row['student_id'] . '" class="edit-button">Edit</a></td>';
            echo '<td><form action="delete_student.php" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this student?\');">';
            echo '<input type="hidden" name="student_id" value="' . $row['student_id'] . '">';
            echo '<input type="submit" value="Delete" class="delete-button">';
            echo '</form></td>';
            echo '</tr>';
        }
        // Close the last table
        echo '</table>';
     
    } else {
        echo 'No students found.';
    }

    $conn->close();
    ?>

<button onclick="window.location.href='admin.php'" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 10px 0;">Back</button>

</div>

</body>
</html>
