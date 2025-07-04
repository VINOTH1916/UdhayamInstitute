cm<?php
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

// Get the quote from the form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["quote"])) {
    $quote = $_POST["quote"];

    // Insert the quote into the database
    $sql = "INSERT INTO quotes (quote, created_at) VALUES (?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $quote);

    if ($stmt->execute()) {
        echo "Quote added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add a Quote</title>
    <style>
       body {
    background-color: black;
    color: #333;
    font-family: Arial, sans-serif;
}

.container {
    width: 80%;
    height: 530px; /* Set the width of the container */
    max-width: 1200px; /* Set the maximum width of the container */
    margin: 0 auto;
    padding-top: 20px;  /* Center the container horizontally */
    padding: 60px; /* Set inner spacing for content within the container */
    background-color: #f6f2f2; /* Set the background color of the container */
    border-radius: 20px; /* Set the border radius to create rounded corners */
    box-shadow: 0px 0px 10px 0px rgba(213, 9, 9, 0.1);
    position: relative;
    top: 500px;
}

.textarea-container {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
}

textarea {
    width: 100%;
    padding: 50px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size:40px;
}

input[type="submit"] {
    background-color: red;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 10px 20px;
    cursor: pointer;
    font-size:40px;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
<div class="container">
<button onclick="window.location.href='admin.php'" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 10px 0;">Home</button>
    <h1>Add a Quote</h1>
    <form method="POST" action="quotes.php">
        <label for="quote"><h2>Your Quote:</h2></label><br>
        <textarea name="quote" id="quote" rows="4" cols="50"></textarea><br>
        <input type="submit" value="Submit">
    </form>
</div>
</body>
</html>
