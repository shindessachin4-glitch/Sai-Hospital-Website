<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "clinic");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$error = ""; // Error message storage

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Check if the admin exists
    $query = "SELECT id, username, password FROM Adminlogin WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify the password
        if (password_verify($password, $row["password"])) {
            // Store admin session
            $_SESSION["admin_id"] = $row["id"];
            $_SESSION["admin_username"] = $row["username"];
            header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No admin account found with this username.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
          body {
           
            display: flex;
            justify-content: center;
            align-items: center;
             margin: 0;
           background: url('12.jpg') no-repeat center center fixed;
           background-size: cover;
           height: 100vh;
           width: 100vw;
            padding: 20px;
        }
        .container {
            padding: 30px;
            border-radius: 8px;
            background: white;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 320px;
        }

        h2 {
            margin-bottom: 20px;
        }

        input, button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .error {
            color: red;
            font-size: 14px;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Admin Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p><a href="index.php">Back to Home</a></p>
    </div>
</body>
</html>
