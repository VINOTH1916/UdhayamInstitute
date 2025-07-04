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
    background-color: #f5f5f5;
    color: #333;
    font-family: Arial, sans-serif;
}

.container {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
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
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 10px 20px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
<div class="container">
    <h1>Add a Quote</h1>
    <form method="POST" action="quates.php">
        <label for="quote">Your Quote:</label><br>
        <textarea name="quote" id="quote" rows="4" cols="50"></textarea><br>
        <input type="submit" value="Submit">
    </form>
</div>
</body>
</html>
