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

// Fetch patients from the correct table
$result = mysqli_query($conn, "SELECT * FROM Patientlogin");

// Check if the query failed
if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

// Count total patients
$total_patients = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient List</title>
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
            max-width: 90%;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow-x: auto; /* Enable horizontal scrolling */
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
        .total-count {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
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

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .container {
                width: 100%;
                padding: 10px;
            }
            table {
                font-size: 14px;
            }
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>

<h2>Patient List</h2>

<div class="container">
    <p class="total-count">Total Patients Found: <?= $total_patients ?></p>

    <?php if ($total_patients > 0) { ?>
        <table>
            <tr>
                <th>Patient ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p style="color: red;">No patients found in the database.</p>
    <?php } ?>

    <a href="admin_dashboard.php" class="back-btn">🔙 Back to Dashboard</a>
</div>

</body>
</html>
