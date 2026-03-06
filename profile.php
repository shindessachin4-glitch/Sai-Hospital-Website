<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "clinic");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Redirect if not logged in
if (!isset($_SESSION["username"]) || !isset($_SESSION["user_type"])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from session
$username = $_SESSION["username"];
$email = isset($_SESSION["email"]) ? $_SESSION["email"] : "Not Provided";
$user_type = $_SESSION["user_type"];
$age = isset($_SESSION["age"]) ? $_SESSION["age"] : "Not Set";  
$specialization = isset($_SESSION["specialization"]) ? $_SESSION["specialization"] : "Not Set"; // Specialization for doctors
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($user_type); ?> Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

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
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        h2 {
            color: #333;
            margin-bottom: 15px;
        }

        p {
            font-size: 18px;
            color: #555;
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            margin: 12px 8px 0;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 15px;
            color: white;
            text-decoration: none;
            transition: 0.3s ease-in-out;
        }

        .btn-edit {
            background-color: #007bff;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }

        .btn-logout {
            background-color: #dc3545;
        }

        .btn-logout:hover {
            background-color: #c82333;
        }

        .btn-home {
            background-color: #28a745;
        }

        .btn-home:hover {
            background-color: #218838;
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px;
                max-width: 90%;
            }

            p {
                font-size: 16px;
            }

            .btn {
                width: 100%;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?></h2>
        
        <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>  
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>

        <?php if ($user_type == "Doctor"): ?>
            <p><strong>Specialization:</strong> <?php echo htmlspecialchars($specialization); ?></p>
        <?php elseif ($user_type == "Patient"): ?>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($age); ?></p>
        <?php endif; ?>

        <a href="edit_profile.php" class="btn btn-edit">Edit Profile</a>
        <a href="logout.php" class="btn btn-logout">Logout</a>
        <a href="index.php" class="btn btn-home">Go To Home</a>
    </div>
</body>
</html>
