<?php
session_start();

$showSuccess = false;

if (isset($_POST['confirm_logout'])) {
    session_destroy();
    $showSuccess = true;
} elseif (isset($_POST['cancel'])) {
    header("Location: dashboard.php"); // Change to your homepage/dashboard
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <?php if ($showSuccess): ?>
        <meta http-equiv="refresh" content="1;url=login.php">
    <?php endif; ?>
    <style>
        * {
            box-sizing: border-box;
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

        }
        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }
        .box {
            background-color: #ffffff;
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 400px;
        }
        h1, h2 {
            margin-bottom: 20px;
            color: #222;
        }
        .btn {
            padding: 12px 25px;
            margin: 10px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .logout-btn {
            background-color: #ff4b5c;
            color: #fff;
        }
        .cancel-btn {
            background-color: #6c757d;
            color: #fff;
        }
        .btn:hover {
            transform: scale(1.05);
        }
        .success-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .success-message {
            font-size: 18px;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="box">
        <?php if ($showSuccess): ?>
            <div class="success-icon">✅</div>
            <h1>Logout Successful</h1>
            <p class="success-message">Redirecting to login page..</p>
        <?php else: ?>
            <form method="post">
                <h2>Are you sure you want to log out?</h2>
                <button type="submit" name="confirm_logout" class="btn logout-btn">Yes, Logout</button>
                <button type="submit" name="cancel" class="btn cancel-btn">Cancel</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
