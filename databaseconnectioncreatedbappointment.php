<?php
// Create connection
$conn = mysqli_connect("localhost" ,"root", "");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
{	
	echo"connecion sucessfully";
	// SQL statement to create a database
$sql = "CREATE DATABASE clinic";
if (mysqli_query($conn,$sql)) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

// Close the connection
$conn->close();
}
?>

