<?php
// Update connection to include the database
$conn = mysqli_connect("localhost","root","","clinic");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL statement to create a table
$sql="CREATE TABLE appointment(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    appointment_date DATE NOT NULL,
    doctor VARCHAR(50) NOT NULL
)";


if ($conn->query($sql) === TRUE) {
    echo "Table appointment created successfully";
} else {
    echo "Error creating table: " . $conn->error;
	
}

// Close the connection
$conn->close();
?>

