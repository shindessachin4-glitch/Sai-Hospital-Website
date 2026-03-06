<?php
$conn = mysqli_connect("localhost", "root", "", "clinic");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch unique doctor names for the filter dropdown
$doctorQuery = "SELECT DISTINCT doctor FROM appointments";
$doctorResult = mysqli_query($conn, $doctorQuery);

// Get selected doctor (if any)
$selectedDoctor = isset($_GET['doctor']) ? $_GET['doctor'] : '';

// Modify query based on the filter
$query = "SELECT id, name AS patient_name, doctor AS doctor_name, appointment_date FROM appointments";
if ($selectedDoctor) {
    $query .= " WHERE doctor = '" . mysqli_real_escape_string($conn, $selectedDoctor) . "'";
}
$query .= " ORDER BY appointment_date DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('header4.png') no-repeat center center fixed;
            background-size: cover;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        select {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 8px 15px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }
        .filter-btn {
            background-color: #28a745;
            color: white;
        }
        .reset-btn {
            background-color: #dc3545;
            color: white;
        }
        button:hover {
            opacity: 0.8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #28a745;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #e0e0e0;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Appointment Dashboard</h2>

    <!-- Filter Form -->
    <form method="GET">
        <label for="doctor">Filter by Doctor:</label>
        <select name="doctor" id="doctor">
            <option value="">-- All Doctors --</option>
            <?php while ($docRow = mysqli_fetch_assoc($doctorResult)) { ?>
                <option value="<?php echo htmlspecialchars($docRow['doctor']); ?>" 
                    <?php if ($selectedDoctor == $docRow['doctor']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($docRow['doctor']); ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit" class="filter-btn">Filter</button>
        <a href="admin_appointments.php"><button type="button" class="reset-btn">Reset</button></a>
    </form>

    <!-- Appointment Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Doctor Name</th>
                <th>Appointment Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo htmlspecialchars($row["patient_name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["doctor_name"]); ?></td>
                    <td><?php echo $row["appointment_date"]; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php" class="back-btn">🔙 Back to Dashboard</a>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
