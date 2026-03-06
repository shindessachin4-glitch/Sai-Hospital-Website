<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "clinic");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$error = "";
$admin_secret_code = "SECURE-1234"; // Change this for security

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $user_type = $_POST["user_type"];
    $entered_code = isset($_POST["secret_code"]) ? trim($_POST["secret_code"]) : "";

    if ($password !== $confirm_password) {
        $error = "Error: Passwords do not match!";
    } else {
        if ($user_type == "Admin") {
            $adminCheckQuery = "SELECT id FROM Adminlogin LIMIT 1";
            $adminCheckResult = mysqli_query($conn, $adminCheckQuery);

            if (mysqli_num_rows($adminCheckResult) > 0) {
                $error = "Error: Admin account already exists!";
            } elseif ($entered_code !== $admin_secret_code) {
                $error = "Error: Invalid secret code!";
            } else {
                $table = "Adminlogin";
            }
        } elseif ($user_type == "Patient") {
            $table = "Patientlogin";
        } else {
            $error = "Invalid user type!";
        }

        if (empty($error)) {
            $checkQuery = "SELECT username FROM Doctorlogin WHERE username = ? 
                        UNION 
                        SELECT username FROM Patientlogin WHERE username = ? 
                        UNION 
                        SELECT username FROM Adminlogin WHERE username = ?";
            $checkStmt = mysqli_prepare($conn, $checkQuery);
            mysqli_stmt_bind_param($checkStmt, "sss", $username, $username, $username);
            mysqli_stmt_execute($checkStmt);
            $result = mysqli_stmt_get_result($checkStmt);

            if (mysqli_num_rows($result) > 0) {
                $error = "Error: Username already exists!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO $table (username, email, password, user_type) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashed_password, $user_type);

                if (mysqli_stmt_execute($stmt)) {
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Error: " . mysqli_error($conn);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
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

        .signup-container {
            width: 350px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input, button, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }

        .bu {
            background: red;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 12px;
            padding: 10px;
            width: 100%;
        }

        .bu:hover {
            background-color: #555;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        @media (max-width: 480px) {
            .signup-container {
                width: 90%;
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Signup</h2>
        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>

            <label for="user_type">Choose User Type:</label>
            <select name="user_type" id="user_type" required onchange="this.form.submit()">
                <option value="Patient" <?php if(isset($_POST['user_type']) && $_POST['user_type'] == "Patient") echo "selected"; ?>>Patient</option>
                <option value="Admin" <?php if(isset($_POST['user_type']) && $_POST['user_type'] == "Admin") echo "selected"; ?>>Admin (Only One Allowed)</option>
            </select>

            <?php if (isset($_POST["user_type"]) && $_POST["user_type"] == "Admin") { ?>
                <input type="password" name="secret_code" placeholder="Admin Secret Code" required>
            <?php } ?>

            <button type="submit">Signup</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
        <a href="index.php"><button class="bu">Go to Home</button></a>
    </div>
</body>
</html>
