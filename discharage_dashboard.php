<?php
session_start();
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "doctor") {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "clinic");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$doctor_email = $_SESSION["email"];

// ✅ Fetch doctor ID & name from session email
$doctor_query = "SELECT id, username FROM doctorlogin WHERE email = ?";
$stmt_doctor = mysqli_prepare($conn, $doctor_query);
mysqli_stmt_bind_param($stmt_doctor, "s", $doctor_email);
mysqli_stmt_execute($stmt_doctor);
$result_doctor = mysqli_stmt_get_result($stmt_doctor);
$doctor_data = mysqli_fetch_assoc($result_doctor);
mysqli_stmt_close($stmt_doctor);

if (!$doctor_data) {
    die("<p style='color: red;'>Error: Doctor not found.</p>");
}

$doctor_id = $doctor_data["id"];
$doctor_name = $doctor_data["username"]; // Fetch doctor name

// ✅ Fetch discharge records for logged-in doctor
$query = "SELECT d.*, p.username AS patient_name 
          FROM discharges d 
          JOIN patientlogin p ON d.patient_id = p.id 
          WHERE d.doctor_id = ? ORDER BY d.discharge_date DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $doctor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Discharge Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
           padding: 0;
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

        .welcome {
            background: #28a745;
            color: white;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 20px;
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
            display: block;
            margin-top: 15px;
            text-decoration: none;
            font-weight: bold;
            
        }
        a:hover {
           color: #007bff;
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
        <!-- ✅ Welcome message for logged-in doctor -->
        <div><h1>Welcome, Dr. <?php echo htmlspecialchars($doctor_name); ?>!</h1></div>
<br>
        <h2>Discharge Dashboard</h2>
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Disease</th>
                    <th>Admission Date</th>
                    <th>Discharge Date</th>
                    <th>Treatment</th>
                    <th>Total Bill (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['disease']); ?></td>
                        <td><?php echo htmlspecialchars($row['admission_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['discharge_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['treatment']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_bill']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
<br>
        
        <p><b><a href="discharge.php" >Discharge Another Patients</a></b></p>
		<p><b><a href="index.php" >🏠Go To Home</a></b></p>
			<p><a href="logout.php"><button class="button">Logout</button></a></p>
    </div>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
