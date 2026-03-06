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

$username = $_SESSION["username"];
$user_type = $_SESSION["user_type"];

// Retrieve appointments based on user type
if ($user_type == "Doctor") {
    $query = "SELECT * FROM appointments WHERE doctor = ? ORDER BY appointment_date ASC";
} else { // Patients only see their own appointments
    $query = "SELECT * FROM appointments WHERE email = (SELECT email FROM Patientlogin WHERE username = ?) ORDER BY appointment_date ASC";
}

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Dashboard</title>
    <style>
      * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body 
		{ 
		font-family: Arial, sans-serif;
		   margin: 0;
          
           background: url('header4.png') no-repeat center center fixed;
           background-size: cover;
           height: 100vh;
           width: 100vw;
		}

.container {
    max-width: 1000px;
    margin: auto;
    background: white;
    padding: 20px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    margin-top: 50px;
    text-align: center;
}

h1, h2 {
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background-color: #28a745;
    color: white;
}

td {
    background-color: #f9f9f9;
}

button {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 12px;
}

button:hover {
    background-color: #218838;
}

a {
    text-decoration: none;
}

.button {
    background-color: #dc3545;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 10px;
}

.button:hover {
    background-color: #c82333;
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

/* Responsive Styles */
@media screen and (max-width: 768px) {
    .container {
        width: 90%;
        padding: 15px;
    }

    table th, table td {
        padding: 10px;
    }

    button {
        padding: 8px 16px;
    }

    h1, h2 {
        font-size: 22px;
    }

    table {
        margin-top: 15px;
    }

    .button {
        margin-top: 15px;
    }
}

@media screen and (max-width: 480px) {
    .container {
        width: 95%;
        padding: 10px;
        margin-top: 20px;
    }

    table th, table td {
        padding: 8px;
    }

    button {
        padding: 6px 12px;
        font-size: 14px;
    }

    h1 {
        font-size: 20px;
    }

    h2 {
        font-size: 18px;
    }

    .button {
        padding: 8px 16px;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <h2>Your Appointments</h2>

        <?php if (mysqli_num_rows($result) > 0) { ?>
        <table>
            <tr>
                <th>Appointment No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Appointment Date</th>
                <th>Doctor</th>
            </tr>
            <?php 
                $serial_no = 1; // Serial number for doctor
                while ($row = mysqli_fetch_assoc($result)) { 
            ?>
                <tr>
                    <td>
                        <?php 
                        if ($user_type == "Doctor") {
                            echo $serial_no++; // Sequential numbering for doctors
                        } else {
                            echo htmlspecialchars($row["id"]); // Actual appointment ID for patients
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($row["name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["email"]); ?></td>
                    <td><?php echo htmlspecialchars($row["appointment_date"]); ?></td>
                    <td><?php echo htmlspecialchars($row["doctor"]); ?></td>
                </tr>
            <?php } ?>
        </table>
        <?php } else { ?>
            <p>No appointments found.</p>
        <?php } ?>

        <br>
        <p><b><a href="appointment.php">📅Book Another Appointment</a></b></p>
        <p><b><a href="index.php">🏠Go to Home</a></b></p>
		<p><a href="logout.php"><button class="button">Logout</button></a></p>
        
    </div>
</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
