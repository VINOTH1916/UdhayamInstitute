<!DOCTYPE html>
<html>
<head>
    <title>Edit Teacher</title>
    <link rel="stylesheet" type="text/css" href="formcss.css">
    <style>
        .container {
            width: 50%;
            margin: 0 auto;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .submit-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        .submit-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Teacher Information</h1>

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

    // Get teacher ID from URL
    $teacher_id = $_GET['id'];

    // Fetch the teacher's information
    $sql = "SELECT * FROM teachers WHERE teacher_id = $teacher_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    ?>

<form action="update_teacher.php" method="POST">
    <input type="hidden" name="teacher_id" value="<?php echo $row['teacher_id']; ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>" required>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required>
    <label for="phone_number">Phone Number:</label>
    <input type="text" id="phone_number" name="phone_number" value="<?php echo $row['phone_number']; ?>" required>
    <label for="date_of_join">Date of Joining:</label>
    <input type="date" id="date_of_join" name="date_of_join" value="<?php echo $row['date_of_join']; ?>" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="<?php echo $row['password']; ?>" required>
    <input type="submit" value="Update Teacher" class="submit-button">
</form>


    <?php
    } else {
        echo 'No teacher found with this ID.';
    }

    $conn->close();
    ?>
</div>

</body>
</html>
