<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "clinic");

if (!$conn) {
    die("<p style='color:red;'>Database connection failed: " . mysqli_connect_error() . "</p>");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Fetch user details including age
    $query = "SELECT username, email, age, password FROM Patientlogin WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        if (!isset($row["password"])) {
            $message = "Error: No password found in the database!";
        } elseif (password_verify($password, $row["password"])) {
            // Store user info in session
            $_SESSION["username"] = $username;
            $_SESSION["user_type"] = "Patient";
            $_SESSION["email"] = $row["email"];
            $_SESSION["age"] = $row["age"];  // Store age in session
            $_SESSION["role"] = "patient";

            $_SESSION["login_success"] = "Login successful! Welcome, $username.";
            header("Location: logindash.php");
            exit();
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "User not found! Please check your username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Login</title>
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
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            background: white;
            width: 300px;
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

        input, button {
            display: block;
            width: 90%;
            padding: 10px;
            margin: 10px 0;
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

       a {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            font-weight: bold;
            
        }
        a:hover {
           color: #007bff;
        }

        .message {
            color: red;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="message"><?php echo $message; ?></div>

    <h1>Patient Login</h1>
    <form method="POST">
        <input type="text" name="username" placeholder="Patient Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login as Patient</button>
    </form>
    <p>Don't have an account? <a href="signup.php">Signup here</a></p>
    <a href="index.php"><button class="bu">Go to Home</button></a>
</div>

</body>
</html>
