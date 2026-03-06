<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "clinic");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch doctors for the filter dropdown
$doctors_query = "SELECT id, username FROM doctorlogin ORDER BY username";
$doctors_result = mysqli_query($conn, $doctors_query);

// Get filter values
$selected_doctor = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : "";

// Fetch treatments with optional filtering by doctor
$query = "SELECT t.id, 
                 p.username AS patient_name, 
                 d.username AS doctor_name, 
                 t.symptoms, t.disease, t.treatment, 
                 t.bill, t.created_at 
          FROM treatments t
          JOIN patientlogin p ON t.patient_id = p.id
          JOIN doctorlogin d ON t.doctor_id = d.id";

if (!empty($selected_doctor)) {
    $query .= " WHERE t.doctor_id = ?";
}

$query .= " ORDER BY t.created_at DESC";

$stmt = mysqli_prepare($conn, $query);
if (!empty($selected_doctor)) {
    mysqli_stmt_bind_param($stmt, "i", $selected_doctor);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Calculate total prescriptions and total bill
$total_prescriptions = 0;
$total_bill = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #eef1f7;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 90%;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 15px;
        }
        form {
            margin-bottom: 20px;
        }
        select, button {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin: 5px;
        }
        .filter-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        .filter-btn:hover {
            background-color: #218838;
        }
        .reset-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        .reset-btn:hover {
            background-color: #c82333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 14px;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        tr:hover {
            background: #f1f1f1;
        }
        .summary-box {
            margin-top: 15px;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            background: #ffc107;
            color: black;
            display: inline-block;
            border-radius: 5px;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: 0.3s;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
        .add-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: 0.3s;
        }
        .add-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>📝 Prescription Dashboard</h1>

    <!-- Doctor Filter Form -->
    <form method="GET" action="">
        <label for="doctor_id"><strong>Filter by Doctor:</strong></label>
        <select name="doctor_id" id="doctor_id">
            <option value="">All Doctors</option>
            <?php while ($doctor = mysqli_fetch_assoc($doctors_result)) { ?>
                <option value="<?php echo $doctor['id']; ?>" 
                    <?php echo ($selected_doctor == $doctor['id']) ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($doctor['username']); ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit" class="filter-btn">Filter</button>
        <button type="button" class="reset-btn" onclick="window.location.href='treatmentdashboard.php'">Reset</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Doctor Name</th>
                <th>Symptoms</th>
                <th>Disease</th>
                <th>Treatment</th>
                <th>Bill Amount (₹)</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { 
                $total_prescriptions++;
                $total_bill += $row["bill"];
            ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo htmlspecialchars($row["patient_name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["doctor_name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["symptoms"]); ?></td>
                    <td><?php echo htmlspecialchars($row["disease"]); ?></td>
                    <td><?php echo htmlspecialchars($row["treatment"]); ?></td>
                    <td>₹<?php echo number_format($row["bill"], 2); ?></td>
                    <td><?php echo $row["created_at"]; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="summary-box">
        📋 Total Prescriptions: <strong><?php echo $total_prescriptions; ?></strong> | 💰 Total Bill: <strong>₹<?php echo number_format($total_bill, 2); ?></strong>
    </div>

    
    <a href="admin_dashboard.php" class="back-btn">🔙 Back to Dashboard</a>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
