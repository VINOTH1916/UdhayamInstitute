<html>
<head>

<link rel="icon" href="logo.png" type="image/png">

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="home.css">
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3763125501779618"
     crossorigin="anonymous"></script>
</head>
<body>


<div id="navbar">
 
  
        <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
        <center><h1 class="page-head-line" style=" color:green;">UDHAYAM INSTITUTE</h1></center>
        <div id="navbar-right">
            <a href="#default" id="logo">
                <div class="logo"><img src="logo.png" height="50px" width="auto"></div>
            </a>
            
        </div>
        <center><p style="font-size:10px; color:white;">கற்க கசடற கற்க! கற்க கற்பவை கற்க!</p></center>
    </div>

    <div id="myNav" class="overlay">
        <div class="overlay-content">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="home.php">HOME</a>
            <a href="service.html">SERVICE</a>
            <a href="teacher/admin.php">ADMIN</a>
            <a href="udhyam.html">CONTACT</a>
            <a href="javascript:void(0)" onclick="toggleLoginSubMenu()">LOGIN</a>
<div id="loginSubMenu" style="display: none;">
    <a href="student/student_login.html" style="padding-left: 20px;">Student</a>
    <a href="teacher/teacher_login.php" style="padding-left: 20px;">Teacher</a>
</div>

        </div>
    </div>

    <!-- Add your additional HTML content here -->








<div class="study-container1">
  <p> comming soon!</p>
<table>
<h2>Class Videos</h2>

     <th>
       
        <img src="youtube-logo.png" alt="YouTube Logo" class="logo1">
</th>

       
</table>
<a href="https://www.youtube.com/@udhayaminstitute" style="font-size:10px;border: 2px solid #007bff; border-radius: 10px; padding: 10px 20px; text-decoration: none; color: #007bff;">Visit Udhaya Institute on YouTube</a>

    </div>
    <br>

    
        <div class="study-container2">
        <p> comming soon!</p>

        <h2>Study Materials</h2>

          <table>
         
            <th>
       
            <img src="drive-logo.png" alt="Google Drive Logo" class="logo1">
</th>

</table>
<a href="https://drive.google.com/drive/folders/1b6-Hm7H5LI1WSL5V-62ZsOB6-_mxe_jH" style=" font-size:10px;border: 2px solid #007bff; border-radius: 10px; padding: 10px 20px; text-decoration: none; color: #007bff;">UDHAYAM INSTITUTE Study Materials</a>

    </div><br>




<script>

let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}


function openNav() {
  document.getElementById("myNav").style.width = "100%";
}

function closeNav() {
  document.getElementById("myNav").style.width = "0%";
}
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
    document.getElementById("navbar").style.padding = "30px 10px";
    document.getElementById("logo").style.fontSize = "25px";
  } else {
    document.getElementById("navbar").style.padding = "80px 10px";
    document.getElementById("logo").style.fontSize = "35px";
  }
}


function toggleLoginSubMenu() {
    var subMenu = document.getElementById("loginSubMenu");
    if (subMenu.style.display === "none") {
        subMenu.style.display = "block";
    } else {
        subMenu.style.display = "none";
    }
}

</script><br>

<div class="quo_container">
<h1>Quotes</h1>

    <h2>Recent Quotes:</h2>
    <ul id="recentQuotes">
        <?php
        // Connect to your database (replace with your database credentials)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "udhayam_institute";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve and display recent quotes
        $sql = "SELECT quote, created_at FROM quotes ORDER BY created_at DESC LIMIT 2";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li class='quoteContainer'>" . htmlspecialchars($row["quote"]) . "<br><small>Posted on " . $row["created_at"] . "</small></li>";
            }
        } else {
            echo "No quotes available.";
        }

        $conn->close();
        ?>
    </ul>

    <button class="seeMoreButton">See More</button>
    <button class="seeLessButton hidden">See Less</button>

    <ul id="allQuotes" class="hidden">
        <?php
        // Connect to your database (replace with your database credentials)
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Retrieve all quotes
        $sql = "SELECT quote, created_at FROM quotes ORDER BY created_at DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li class='quoteContainer'>" . htmlspecialchars($row["quote"]) . "<br><small>Posted on " . $row["created_at"] . "</small></li>";
            }
        } else {
            echo "No quotes available.";
        }

        $conn->close();
        ?>
    </ul>

    <script>
        // JavaScript code to handle the "See More" and "See Less" functionality
        var recentQuotes = document.getElementById("recentQuotes");
        var allQuotes = document.getElementById("allQuotes");
        var seeMoreButton = document.querySelector(".seeMoreButton");
        var seeLessButton = document.querySelector(".seeLessButton");

        seeMoreButton.addEventListener("click", function() {
            recentQuotes.style.display = "none";
            allQuotes.style.display = "block";
            seeMoreButton.classList.add("hidden");
            seeLessButton.classList.remove("hidden");
        });

        seeLessButton.addEventListener("click", function() {
            recentQuotes.style.display = "block";
            allQuotes.style.display = "none";
            seeMoreButton.classList.remove("hidden");
            seeLessButton.classList.add("hidden");
        });
    </script>
 

      </div>

      <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3763125501779618"
     crossorigin="anonymous"></script>
<!-- Home -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-3763125501779618"
     data-ad-slot="6135668823"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>


      <footer>
    <div class="footer-content">
        <div class="footer-left">
            <p>&copy; 2023 UDHAYAM INSTITUTE. All rights reserved.</p>
            <p>Contact: info@udhayaminstitute.com</p>
        </div>
        <div class="footer-right">
            <h3>திருக்குறள் அதிகாரம் 42 – கேள்வி</h3>
            <p>குறள் 412:<br> செவிக்குண வில்லாத போழ்து சிறிது <br> வயிற்றுக்கும் ஈயப் படும்</p>
        </div>
    </div>
</footer>

</body>
</html>