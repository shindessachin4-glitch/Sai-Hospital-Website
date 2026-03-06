<?php
// Database Connection
$conn = mysqli_connect("localhost", "root", "", "clinic");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get selected doctor from filter (if any)
$selectedDoctor = isset($_GET["doctor_id"]) ? $_GET["doctor_id"] : "";

// Fetch doctor list for the filter dropdown
$doctorQuery = "SELECT id, username FROM doctorlogin ORDER BY username";
$doctorResult = mysqli_query($conn, $doctorQuery);

// Fetch discharge records with optional filtering
$query = "SELECT d.id, 
                 COALESCE(p.username, 'Unknown Patient') AS patient_name, 
                 d.admission_date, d.discharge_date, 
                 d.treatment, d.total_bill, d.disease, 
                 COALESCE(doc.username, 'Unknown Doctor') AS doctor_name, 
                 COALESCE(doc.email, 'No Email') AS doctor_email
          FROM discharges d
          LEFT JOIN patientlogin p ON d.patient_id = p.id
          LEFT JOIN doctorlogin doc ON d.doctor_id = doc.id";

if (!empty($selectedDoctor)) {
    $query .= " WHERE d.doctor_id = '$selectedDoctor'";
}

$query .= " ORDER BY d.discharge_date DESC";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

// Calculate total bill and count patients
$totalBill = 0;
$totalPatients = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discharge Records</title>
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
        .total-row {
            font-weight: bold;
            background-color: #007bff;
            color: white;
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
    </style>
</head>
<body>

<div class="container">
    <h1>🏥 Discharge Records</h1>

    <!-- Doctor Filter Form -->
    <form method="GET" action="">
        <label for="doctor_id"><strong>Filter by Doctor:</strong></label>
        <select name="doctor_id" id="doctor_id">
            <option value="">All Doctors</option>
            <?php while ($doctor = mysqli_fetch_assoc($doctorResult)) { ?>
                <option value="<?php echo $doctor['id']; ?>" 
                    <?php echo ($selectedDoctor == $doctor['id']) ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($doctor['username']); ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit" class="filter-btn">Filter</button>
        <button type="button" class="reset-btn" onclick="window.location.href='admin_discharge_dashboard.php'">Reset</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Disease</th>
                <th>Treatment</th>
                <th>Admission Date</th>
                <th>Discharge Date</th>
                <th>Total Bill (₹)</th>
                <th>Doctor Name</th>
                <th>Doctor Email</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { 
                $totalBill += $row["total_bill"];
                $totalPatients++;
            ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo htmlspecialchars($row["patient_name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["disease"]); ?></td>
                    <td><?php echo htmlspecialchars($row["treatment"]); ?></td>
                    <td><?php echo $row["admission_date"]; ?></td>
                    <td><?php echo $row["discharge_date"]; ?></td>
                    <td>₹<?php echo number_format($row["total_bill"], 2); ?></td>
                    <td><?php echo htmlspecialchars($row["doctor_name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["doctor_email"]); ?></td>
                </tr>
            <?php } ?>
           
        </tbody>
    </table>

    <div class="summary-box">
        🏥 Total Patients: <strong><?php echo $totalPatients; ?></strong> | 💰 Sum Total Bill: <strong>₹<?php echo number_format($totalBill, 2); ?></strong>
    </div>

    <a href="admin_dashboard.php" class="back-btn">🔙 Back to Dashboard</a>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
