<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add your meta tags, title, and styles here -->
    <style>
        /* Add your existing styles here */

        .attendance-slide {
            display: none;
            animation: slideIn 0.5s ease-in-out;
        }

        @keyframes slideIn {
            0% {
                opacity: 0;
                transform: translateY(50px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header and navigation buttons for different grades -->
        <header>
            <h1>Mark Student Attendance</h1>
        </header>

        <div class="navigation-buttons">
            <button onclick="showGrade('grade10')">10th Grade</button>
            <button onclick="showGrade('grade11')">11th Grade</button>
            <button onclick="showGrade('grade12')">12th Grade</button>
        </div>

        <!-- 10th Grade Attendance Table -->
        <div id="grade10" class="attendance-slide">
            <form action="process_attendance.php" method="POST">
                <!-- 10th Grade Attendance Table (Your existing code) -->
                <!-- Modify the PHP loop and HTML structure for 10th grade students -->
            </form>
        </div>

        <!-- 11th Grade Attendance Table -->
        <div id="grade11" class="attendance-slide">
            <form action="process_attendance.php" method="POST">
                <!-- 11th Grade Attendance Table HTML and PHP Loop -->
            </form>
        </div>

        <!-- 12th Grade Attendance Table -->
        <div id="grade12" class="attendance-slide">
            <form action="process_attendance.php" method="POST">
                <!-- 12th Grade Attendance Table HTML and PHP Loop -->
            </form>
        </div>
    </div>

    <!-- Add your existing footer and scripts here -->

    <script>
        function showGrade(grade) {
            var slides = document.getElementsByClassName('attendance-slide');
            for (var i = 0; i < slides.length; i++) {
                slides[i].style.display = 'none';
            }
            document.getElementById(grade).style.display = 'block';
        }
    </script>
</body>

</html>
