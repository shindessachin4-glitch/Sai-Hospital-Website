<?php
session_start();
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "doctor") {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "clinic");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$doctor_email = $_SESSION["email"];

$query = "SELECT * FROM Admissions WHERE doctor_email = ? ORDER BY id DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $doctor_email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
           background: url('header4.png') no-repeat center center fixed;
           background-size: cover;
           height: 100vh;
           width: 100vw;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #28a745;
            color: white;
        }
        .bu {
            background-color: #dc3545;
            color: white;
            padding: 7px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .bu:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>



<div class="container">
<h1>Welcome, Dr. <?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
    <h2>Your Admitted Patients</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Disease</th>
                <th>Bed Number</th> <!-- Added Bed Number column -->
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['contact']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['disease']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['bed_number']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No admitted patients found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <p><b><a href="admission.php" class="button">Admit New Patient</a></b></p>
	 <p><b><a href="discharge.php">Discharge Patient</a></b></p>
    <p><b><a href="index.php"> Go To Home</a></b></p>
    <p><a href="logout.php" class="bu">Logout</a></p>

</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
