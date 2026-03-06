<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "clinic");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Ensure the doctor is logged in
function checkDoctorSession() {
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "doctor") {
        die("<p style='color:red;'>Error: Unauthorized access. Please log in as a doctor.</p>");
    }
    if (!isset($_SESSION["doctor_id"])) {
        die("<p style='color:red;'>Error: Doctor ID is missing in the session.</p>");
    }
}

checkDoctorSession();
$doctor_id = $_SESSION["doctor_id"];

// Filter by date (if selected)
$filter_date = isset($_GET['date']) ? $_GET['date'] : "";

// Fetch treatments added by this doctor with Symptoms and Bill
$query = "SELECT t.id, p.username AS patient_name, t.symptoms, t.disease, t.treatment, t.bill, t.created_at 
          FROM treatments t
          JOIN patientlogin p ON t.patient_id = p.id
          WHERE t.doctor_id = ?";

if (!empty($filter_date)) {
    $query .= " AND DATE(t.created_at) = ?";
}

$query .= " ORDER BY t.created_at DESC";

$stmt = mysqli_prepare($conn, $query);
if (!empty($filter_date)) {
    mysqli_stmt_bind_param($stmt, "is", $doctor_id, $filter_date);
} else {
    mysqli_stmt_bind_param($stmt, "i", $doctor_id);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            text-align: center;
            background: url('header4.png') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            width: 100vw;
        }
        .container {
            width: 90%;
            margin: auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #28a745;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        select, input, button {
            padding: 8px;
            margin: 10px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
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
    <h1>Prescription History</h1>

    <!-- Filter by Date -->
    <form method="GET">
        <label>Select Date:</label>
        <input type="date" name="date" value="<?php echo htmlspecialchars($filter_date); ?>">
        <button type="submit">Filter</button>
        <a href="treatmentdashboard.php"><button type="button">Reset</button></a>
    </form>

    <table>
        <tr>
            <th>Patient Name</th>
            <th>Symptoms</th>
            <th>Disease</th>
            <th>Treatment</th>
            <th>Bill (₹)</th>
            <th>Date</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                <td><?php echo htmlspecialchars($row['symptoms']); ?></td>
                <td><?php echo htmlspecialchars($row['disease']); ?></td>
                <td><?php echo htmlspecialchars($row['treatment']); ?></td>
                <td>₹<?php echo htmlspecialchars(number_format($row['bill'], 2)); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
            </tr>
        <?php } ?>
    </table>

    <p><b><a href="add_treatment.php" class="button">Add New Prescription</a></b></p>
    <p><b><a href="index.php"> Go To Home</a></b></p>
    <p><a href="logout.php" class="bu">Logout</a></p>
</div>
</body>
</html>
