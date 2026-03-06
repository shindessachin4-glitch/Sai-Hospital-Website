<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "clinic");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Ensure only admin can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch admitted patients data
$query = "SELECT a.id, a.patient_name, a.age, a.gender, a.address, a.contact, 
                 a.disease, a.bed_number, a.admission_date, 
                 d.username AS doctor_name
          FROM Admissions a
          JOIN doctorlogin d ON a.doctor_email = d.email
          ORDER BY a.admission_date DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        h2 {
            background: #007bff;
            color: white;
            padding: 15px;
            margin: 0;
        }
        .container {
            margin: 20px;
            padding: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .back-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<h2>Admin Dashboard - Admitted Patients</h2>

<div class="container">
    <table>
        <tr>
            <th>Patient Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Address</th>
            <th>Contact</th>
            <th>Disease</th>
            <th>Bed Number</th>
            <th>Doctor Name</th>
            <th>Admission Date</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['patient_name']}</td>
                        <td>{$row['age']}</td>
                        <td>{$row['gender']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['contact']}</td>
                        <td>{$row['disease']}</td>
                        <td>{$row['bed_number']}</td>
                        <td>Dr. {$row['doctor_name']}</td>
                        <td>{$row['admission_date']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='9' style='text-align: center;'>No admitted patients found</td></tr>";
        }
        ?>
    </table>

    <a href="admin_dashboard.php" class="back-btn">🔙 Back to Admin Panel</a>
</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
