<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" type="text/css" href="formcss.css">
    <style>
        .container {
            width: 50%;
            margin: 0 auto;
            height: 1080px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input, select {
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
    <h1>Edit Student Information</h1>

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

    // Get student ID from URL
    $student_id = $_GET['id'];

    // Fetch the student's information
    $sql = "SELECT * FROM students WHERE student_id = $student_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    ?>

    <form action="update_student.php" method="POST">
        <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
        <label for="roll_no">Roll Number:</label>
        <input type="text" id="roll_no" name="roll_no" value="<?php echo $row['roll_no']; ?>" readonly>
        <label for="student_name">Student Name:</label>
        <input type="text" id="student_name" name="student_name" value="<?php echo $row['student_name']; ?>" required>
        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo $row['date_of_birth']; ?>" required onchange="calculateAge()">
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" value="<?php echo $row['age']; ?>" required readonly>
        <label for="date_of_join">Date of Join:</label>
        <input type="date" id="date_of_join" name="date_of_join" value="<?php echo $row['date_of_join']; ?>" required>
        <label for="mat_cbsc">Matriculation/CBSE:</label>
        <select id="mat_cbsc" name="mat_cbsc" required>
            <option value="mat" <?php if($row['mat_cbsc'] == 'mat') echo 'selected'; ?>>Matriculation</option>
            <option value="cbsc" <?php if($row['mat_cbsc'] == 'cbsc') echo 'selected'; ?>>CBSE</option>
        </select>
        <label for="blood_grp">Blood Group:</label>
        <select id="blood_grp" name="blood_grp" required>
            <option value="" disabled>Select your blood group</option>
            <option value="A+" <?php if($row['blood_grp'] == 'A+') echo 'selected'; ?>>A+</option>
            <option value="A-" <?php if($row['blood_grp'] == 'A-') echo 'selected'; ?>>A-</option>
            <option value="B+" <?php if($row['blood_grp'] == 'B+') echo 'selected'; ?>>B+</option>
            <option value="B-" <?php if($row['blood_grp'] == 'B-') echo 'selected'; ?>>B-</option>
            <option value="AB+" <?php if($row['blood_grp'] == 'AB+') echo 'selected'; ?>>AB+</option>
            <option value="AB-" <?php if($row['blood_grp'] == 'AB-') echo 'selected'; ?>>AB-</option>
            <option value="O+" <?php if($row['blood_grp'] == 'O+') echo 'selected'; ?>>O+</option>
            <option value="O-" <?php if($row['blood_grp'] == 'O-') echo 'selected'; ?>>O-</option>
        </select>
        <label for="school_name">School Name:</label>
        <input type="text" id="school_name" name="school_name" value="<?php echo $row['school_name']; ?>" required>
        <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required>
        <label for="ph_no">Phone Number:</label>
        <input type="text" id="ph_no" name="ph_no" value="<?php echo $row['ph_no']; ?>" required>
        <label for="fees">Fees:</label>
        <input type="number" step="0.01" id="fees" name="fees" value="<?php echo $row['fees']; ?>" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="<?php echo $row['password']; ?>" required>
        <input type="submit" value="Update Student" class="submit-button">
    </form>

    <?php
    } else {
        echo 'No student found with this ID.';
    }

    $conn->close();
    ?>
</div>

<script>
function calculateAge() {
    var dob = document.getElementById("date_of_birth").value;
    var dobDate = new Date(dob);
    var today = new Date();
    var age = today.getFullYear() - dobDate.getFullYear();
    var monthDifference = today.getMonth() - dobDate.getMonth();

    // Adjust age if the birth date has not yet occurred this year
    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dobDate.getDate())) {
        age--;
    }

    document.getElementById("age").value = age;
}
</script>

</body>
</html>
