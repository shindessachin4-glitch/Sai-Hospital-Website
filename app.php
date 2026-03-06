<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "clinic";

$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ensure the table exists
$table_query = "CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    appointment_date DATE NOT NULL,
    doctor VARCHAR(50) NOT NULL
)";
mysqli_query($conn, $table_query);

// Capture form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $doctor = mysqli_real_escape_string($conn, $_POST['doctor']);

    // Insert into database
    $sql = "INSERT INTO appointments (name, email, appointment_date, doctor) 
            VALUES ('$name', '$email', '$appointment_date', '$doctor')";

    if (mysqli_query($conn, $sql)) {
        echo "Appointment booked successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Close connection
mysqli_close($conn);
?>
