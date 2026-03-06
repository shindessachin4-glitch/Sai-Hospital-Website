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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $specialization = trim($_POST["specialization"]);

    if ($password !== $confirm_password) {
        $error = "Error: Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new doctor into doctorlogin table
        $query = "INSERT INTO doctorlogin (username, email, password, specialization) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashed_password, $specialization);

        if (mysqli_stmt_execute($stmt)) {
            // ✅ Fetch the new doctor's ID
            $doctor_id = mysqli_insert_id($conn);
            $success = "Doctor added successfully with ID: $doctor_id";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Doctor</title>
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
            height: 100vh;
        }
       
        .container {
            max-width: 400px;
            width: 90%;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        .message {
            font-size: 14px;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .error {
            background: #ffb3b3;
            color: red;
        }
        .success {
            background: #b3ffb3;
            color: green;
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
        
        /* Responsive Design */
        @media screen and (max-width: 480px) {
            .container {
                width: 95%;
            }
            input {
                font-size: 14px;
                padding: 8px;
            }
            button {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Doctor</h2>
    <?php if (!empty($error)) { echo "<p class='message error'>$error</p>"; } ?>
    <?php if (!empty($success)) { echo "<p class='message success'>$success</p>"; } ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Doctor Username" required>
        <input type="email" name="email" placeholder="Doctor Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="text" name="specialization" placeholder="Specialization" required>
        <button type="submit">Add Doctor</button>
    </form>

    <a href="admin_dashboard.php" class="back-btn">🔙 Back to Dashboard</a>
</div>

</body>
</html>
