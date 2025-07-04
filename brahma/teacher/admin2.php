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

    <style> 
    p { 
        font-size: 40px; 
    }


    form {
    background-color: #fff;
    margin: 20px;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}


/* Style form labels */

label {
    display: block;
    margin-top: 10px;
}


/* Style select and input elements */

select,
input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 10px;
}


/* Style the search button */

input[type="submit"] {
    background-color: #337ab7;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #235a9c;
}


    button {
    display: block;
    text-decoration: none;
    padding: 40px;
    margin: 10px 0;
    background-color: #010901;
    color: white;
    border-radius: 5px;
    text-align: center;
    font-size: 40px;
    width: 100%;
}

    .modal {
        display: none; 
        position: fixed; 
        z-index: 1; 
        padding-top: 100px; 
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%; 
        overflow: auto; 
        background-color: rgb(0,0,0); 
        background-color: rgba(0,0,0,0.4); 
    }
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
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
    <div class="admin-header">
        <h1 style="border: 2px solid black;background-color:white;color:green;border-radius: 100px;">UDHAYAM INSTITUTE</h1>
        <h1>Welcome to the Admin Panel</h1>
    </div>

    <div class="container">
        <div class="admin-menu">
            <table class="menu-table">
                <tr>
                    <td><a href="archived_student_list.php" class="menu-button button6">Archived Students</a></td>
                </tr>
                 
                 
                <tr>
                    <td><a href="search_attendance.php" class="menu-button button6">Old Attendance</a></td>
                </tr>

                <tr>
                    <td><a href="archived_teacher_list.php" class="menu-button button6">Archived Teacher</a></td>
                </tr>
                <tr>
                    <td><button id="delete-all-button" class="delete-all-button">Delete All Students</button></td>
                </tr>
                <tr>
                    <td><a href="admin.php" class="menu-button button6">Home</a></td>
                </tr>
            </table>
        </div>

        <p><a href="forgot_password.php" class="forgot-password">Change admin Password?</a></p>
    </div>

    

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Admin Password Required</h2>
            <form action="truncate_students.php" method="POST">
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
