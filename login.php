<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Selection</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #5694ce;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: url('12.jpg') no-repeat center center fixed;
            background-size: cover;
            width: 100vw;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            text-decoration: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            text-align: center;
            transition: 0.3s ease-in-out;
        }

        .doctor-btn {
            background: #28a745; /* Green for Doctor */
        }

        .doctor-btn:hover {
            background: #218838;
        }

        .patient-btn {
            background: #007bff; /* Blue for Patient */
        }

        .patient-btn:hover {
            background: #0056b3;
        }

        .admin-btn {
            background: #dc3545; /* Red for Admin */
        }

        .admin-btn:hover {
            background: #c82333;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            h1 {
                font-size: 20px;
            }

            .btn {
                font-size: 14px;
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
                width: 90%;
            }

            h1 {
                font-size: 18px;
            }

            .btn {
                font-size: 14px;
                padding: 8px;
            }

            /* Stack buttons vertically for mobile */
            .btn {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Login as</h1>
    <a href="doctorlogin.php" class="btn doctor-btn">Doctor Login</a>
    <a href="patientlogin.php" class="btn patient-btn">Patient Login</a>
    <a href="admin_login.php" class="btn admin-btn">Admin Login</a> <!-- Admin Login Added -->
    <br>
    <p>Don't have an account? <b><a href="signup.php">Signup</a></b></p><br>
    <b><a href="index.php">Go To Home</a></b>
</div>

</body>
</html>
