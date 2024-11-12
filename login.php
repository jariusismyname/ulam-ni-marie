<?php
session_start();

// Database configuration
$servername = "localhost";  // Change if your DB is hosted elsewhere
$db_username = "root";      // Your DB username
$db_password = "";          // Your DB password (default is empty for XAMPP)
$dbname = "your_database";  // Replace with your database name

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Protect against SQL injection
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    // Query to find the user
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Assuming passwords are hashed in the database
        if (password_verify($password, $row['password'])) {
            // Successful login
            $_SESSION['username'] = $username;
            header("Location: welcome.php");  // Redirect to a welcome page
            exit;
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No user found with that username.";
    }
}

$conn->close();
?>
