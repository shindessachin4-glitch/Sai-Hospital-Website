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

$error = "";
$success = "";

// Remove doctor if ID is provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['doctor_id'])) {
    $doctor_id = $_POST['doctor_id'];

    // Delete the doctor from doctorlogin table
    $delete_query = "DELETE FROM doctorlogin WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $doctor_id);

    if (mysqli_stmt_execute($stmt)) {
        $success = "Doctor removed successfully.";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Fetch all doctors
$doctors_query = "SELECT id, username, email, specialization FROM doctorlogin";
$doctors_result = mysqli_query($conn, $doctors_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Doctor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            max-width: 600px;
            width: 90%;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        .delete-btn {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .delete-btn:hover {
            background: #c82333;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Remove Doctor</h2>

    <?php if (!empty($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    <?php if (!empty($success)) { echo "<p style='color:green;'>$success</p>"; } ?>

    <h3>Doctor List</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Specialization</th>
            <th>Action</th>
        </tr>
        <?php while ($doctor = mysqli_fetch_assoc($doctors_result)) { ?>
            <tr>
                <td><?php echo $doctor['id']; ?></td>
                <td><?php echo $doctor['username']; ?></td>
                <td><?php echo $doctor['email']; ?></td>
                <td><?php echo $doctor['specialization']; ?></td>
                <td>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to remove this doctor?');">
                        <input type="hidden" name="doctor_id" value="<?php echo $doctor['id']; ?>">
                        <button type="submit" class="delete-btn">Remove</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <a href="admin_dashboard.php" class="back-btn">🔙 Back to Dashboard</a>
</div>

</body>
</html>
