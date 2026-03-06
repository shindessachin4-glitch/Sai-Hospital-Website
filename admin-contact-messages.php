<?php
// Start session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "clinic");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Enable error reporting for debugging (optional)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch contact messages
$query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Messages - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e3f2fd;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #002D62;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #002D62;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f3f3f3;
        }

        .no-msg {
            text-align: center;
            padding: 20px;
            font-size: 16px;
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📬 Contact Messages</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Sender Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <div class="no-msg">No messages found.</div>
    <?php endif; ?>
</div>

</body>
</html>
