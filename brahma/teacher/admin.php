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
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="admin.css">

<style> p
{ font-size: 40px;
}</style>

</head>

<body>
    <div class="admin-header">
    <h1 style="border: 2px solid black;background-color:white;color:green;border-radius: 100px;">UDHAYAM INSTITUTE</h1>

        <h1>Welcome to the Admin Panel</h1>
    </div>

    <div class="container"><div class="admin-menu">
    <table class="menu-table">

    <tr>
            <td><a href="add_teacher.php" class="menu-button button6">Add teacher</a></td>
        </tr>

        <tr>
            <td><a href="teacher_list.php" class="menu-button button6">teacher</a></td>
        </tr>

        <tr>
            <td><a href="admin_process.php" class="menu-button button1">Add Student</a></td>
        </tr>

        <tr>
            <td><a href="student_list.php" class="menu-button button6">students</a></td>
        </tr>

        <tr>
            <td><a href="view_attendance.php" class="menu-button button2">View Student Attendance</a></td>
      
        </tr>
       
        <tr>
            <td><a href="view_student.php" class="menu-button button3">View Student Bio Data</a></td>
        </tr>
       
        
        <tr>
            <td><a href="attendance.php" class="menu-button button6">Take Attendance</a></td>
        </tr>
       

      

        <tr>
            <td><a href="quotes.php" class="menu-button button6">add quotes</a></td>
        </tr>

        <tr>
            <td><a href="admin2.php" class="menu-button button6">others</a></td>
        </tr>

        </div>
   

    </div>

    
    </table>

    <p><a href="forgot_password.php" class="forgot-password">Change admin Password?</a></p>
</div>
</div>

</body>
</html>
