v<!DOCTYPE html>
<html>
<head>
    <title>Quotes</title>
    <style>
        /* Add your CSS styles here */
        .hidden {
            display: none;
        }

        .quoteContainer {
            background-color: #f9f9f9;
            padding: 10px;
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
        }

        .seeMoreButton, .seeLessButton {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            margin-top: 20px;
        }

        .seeMoreButton:hover, .seeLessButton:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
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
</body>
</html>
