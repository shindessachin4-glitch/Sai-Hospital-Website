<?php
// Update connection to include the database
$conn = new mysqli("localhost", "root", "", "clinic");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL statement to create a table
$sql="CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";



if ($conn->query($sql) === TRUE) {
    echo "Table appointment created successfully";
} else {
    echo "Error creating table: " . $conn->error;
	
}

// Close the connection
$conn->close();
?>

