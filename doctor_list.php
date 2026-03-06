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

// Fetch doctors
$result = mysqli_query($conn, "SELECT * FROM doctorlogin");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor List</title>
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
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 90%;
            overflow-x: auto; /* Enable horizontal scrolling on small screens */
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
        
        /* Responsive Table */
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

<h2>Doctor List</h2>

<div class="container">
    <table>
        <tr>
            <th>Doctor ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Specialization</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['username'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['specialization'] ?></td>
            </tr>
        <?php } ?>
    </table>

    <a href="admin_dashboard.php" class="back-btn">🔙 Back to Dashboard</a>
</div>

</body>
</html>
