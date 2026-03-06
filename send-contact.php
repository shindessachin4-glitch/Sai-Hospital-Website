<?php
// Database connection
$host = "localhost";
$user = "root"; // change this if needed
$pass = "";     // change this if needed
$db = "railway"; // your database name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and sanitize form input
$name = $conn->real_escape_string($_POST['name']);
$email = $conn->real_escape_string($_POST['email']);
$subject = $conn->real_escape_string($_POST['subject']);
$message = $conn->real_escape_string($_POST['message']);

// Insert into database
$sql = "INSERT INTO contact_messages (name, email, subject, message) 
        VALUES ('$name', '$email', '$subject', '$message')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('✅ Message sent successfully!');
            window.location.href='contact1.html';
          </script>";
} else {
    echo "<script>
            alert('❌ Error sending message. Try again.');
            window.location.href='contact1.html';
          </script>";
}

$conn->close();
?>
