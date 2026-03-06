<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "clinic");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Ensure user is logged in
if (!isset($_SESSION["username"]) || !isset($_SESSION["user_type"])) {
    header("Location: login.php");
    exit();
}

$logged_in_username = $_SESSION["username"];
$user_type = $_SESSION["user_type"];

// Fetch user name and email (based on user type)
if ($user_type == "Patient") {
    $user_query = "SELECT username, email FROM Patientlogin WHERE username = ?";
} else { // Doctor
    $user_query = "SELECT username, email FROM Doctorlogin WHERE username = ?";
}

$stmt_user = mysqli_prepare($conn, $user_query);
mysqli_stmt_bind_param($stmt_user, "s", $logged_in_username);
mysqli_stmt_execute($stmt_user);
$user_result = mysqli_stmt_get_result($stmt_user);
$user = mysqli_fetch_assoc($user_result);
$logged_in_name = $user['username'] ?? ''; // Using 'username' instead of 'name'
$logged_in_email = $user['email'] ?? ''; // Ensure email is not null
mysqli_stmt_close($stmt_user);

// Fetch doctor list
$doctor_query = "SELECT username FROM Doctorlogin"; // Change 'name' to 'username'
$doctor_result = mysqli_query($conn, $doctor_query);

// Fetch patient list (Doctors only)
if ($user_type == "Doctor") {
    $patient_query = "SELECT username FROM Patientlogin"; // Change 'name' to 'username'
    $patient_result = mysqli_query($conn, $patient_query);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_date = $_POST["appointment_date"];
    $doctor = trim($_POST["doctor"]);

    if ($user_type == "Patient") {
        $patient_name = $logged_in_name;
        $email = $logged_in_email;
    } else { // Doctor booking for a patient
        $patient_name = trim($_POST["patient"]);

        // Verify patient exists
        $patient_check_query = "SELECT email FROM Patientlogin WHERE username = ?";
        $stmt_patient_check = mysqli_prepare($conn, $patient_check_query);
        mysqli_stmt_bind_param($stmt_patient_check, "s", $patient_name);
        mysqli_stmt_execute($stmt_patient_check);
        $patient_check_result = mysqli_stmt_get_result($stmt_patient_check);
        $patient_data = mysqli_fetch_assoc($patient_check_result);
        mysqli_stmt_close($stmt_patient_check);

        if (!$patient_data) {
            echo "<script>alert('Error: Patient does not exist.');</script>";
            exit();
        }

        $email = $patient_data['email'];
    }

    // Validate input fields
    if (!empty($appointment_date) && !empty($doctor)) {
        $query = "INSERT INTO appointments (name, email, appointment_date, doctor) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $patient_name, $email, $appointment_date, $doctor);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Appointment booked successfully!'); window.location='appointdashboard.php';</script>";
        } else {
            echo "<script>alert('Error booking appointment. Try again!');window.location='appointment.php';</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <style>
        * {
			margin: 0; 
			padding: 0; 
			box-sizing:
			border-box; 
			}
			
        body 
		{ 
		font-family: Arial, sans-serif;
		   margin: 0;
         
           background: url('12.jpg') no-repeat center center fixed;
           background-size: cover;
           height: 100vh;
           width: 100vw;
		}
        .container 
		{ 
		max-width: 600px;
		margin: auto; 
		background: white;
		padding: 30px; 
		box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); 
		margin-top: 50px;
		}
        h2
		{
			text-align: center; 
			margin-bottom: 20px; 
			}
        input, select, button
		{ 
		width: 100%;
		margin: 10px 0;
		padding: 12px; 
		border: 1px solid #ddd;
		border-radius: 5px;
		font-size: 16px; 
		}
        button 
		{ 
		background: #28a745; 
		color: white; 
		border: none; 
		cursor: pointer;
		}
        button:hover 
		{
			background: #218838;
			}
        label 
		{ 
		font-weight: bold; 
		margin-bottom: 5px;
		display: block; 
		}
		
        .text 
		{
			text-align: center;
			margin-top: 20px;
			}
			a {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            font-weight: bold;
            
        }
        a:hover {
           color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Book an Appointment</h2>
        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($logged_in_name); ?>" readonly>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($logged_in_email); ?>" readonly>

            <?php if ($user_type == "Doctor") { ?>
                <label>Select Patient:</label>
                <select name="patient" required>
                    <option value="">-- Select Patient --</option>
                    <?php while ($patient = mysqli_fetch_assoc($patient_result)) { ?>
                        <option value="<?php echo $patient['username']; ?>"><?php echo $patient['username']; ?></option>
                    <?php } ?>
                </select>
            <?php } ?>

            <label>Appointment Date:</label>
            <input type="date" name="appointment_date" required>
            
            <label>Select Doctor:</label>
            <select name="doctor" required>
                <option value="">-- Select Doctor --</option>
                <?php while ($doctor = mysqli_fetch_assoc($doctor_result)) { ?>
                    <option value="<?php echo $doctor['username']; ?>"><?php echo $doctor['username']; ?></option>
                <?php } ?>
            </select>
            
            <button type="submit">Book Appointment</button>
        </form>
        <br>
        <p><b><a href="appointdashboard.php">View My Appointments</a></b></p>
        <p><b><a href="index.php"> Go To Home</a></b></p>
    </div>
</body>
</html>
