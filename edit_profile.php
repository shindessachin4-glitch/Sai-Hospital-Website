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

$old_username = $_SESSION["username"];
$user_type = $_SESSION["user_type"];
$message = "";

// Fetch current user details
$query = ($user_type == "Doctor") 
    ? "SELECT username, email, specialization, password FROM Doctorlogin WHERE username = ?" 
    : "SELECT username, email, age, password FROM Patientlogin WHERE username = ?";
    
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $old_username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

$username = $row["username"];
$email = $row["email"];
$age_or_specialization = $user_type == "Doctor" ? $row["specialization"] : $row["age"];
$hashed_password = $row["password"]; // Store hashed password from DB

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $old_password = trim($_POST["old_password"]);
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    
    $updatePassword = false;

    // Validate username uniqueness
    $checkQuery = "SELECT username FROM Doctorlogin WHERE username = ? AND username != ?
                   UNION 
                   SELECT username FROM Patientlogin WHERE username = ? AND username != ?";
    $checkStmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, "ssss", $new_username, $old_username, $new_username, $old_username);
    mysqli_stmt_execute($checkStmt);
    $result = mysqli_stmt_get_result($checkStmt);
    
    if (mysqli_num_rows($result) > 0) {
        $message = "<span style='color:red;'>Error: Username already exists!</span>";
    } 
    // Validate email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<span style='color:red;'>Invalid email format!</span>";
    } 
    else {
        // Password change logic
        if (!empty($old_password) || !empty($new_password) || !empty($confirm_password)) {
            if (empty($old_password)) {
                $message = "<span style='color:red;'>Old password is required to change password!</span>";
            } elseif (!password_verify($old_password, $hashed_password)) {
                $message = "<span style='color:red;'>Old password is incorrect!</span>";
            } elseif ($new_password !== $confirm_password) {
                $message = "<span style='color:red;'>New passwords do not match!</span>";
            } elseif (strlen($new_password) < 8 || !preg_match('/[A-Za-z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
                $message = "<span style='color:red;'>New password must be at least 8 characters long and contain letters and numbers!</span>";
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $updatePassword = true;
            }
        }

        if (empty($message)) {
            if ($user_type == "Doctor") {
                $specialization = trim($_POST["specialization"]);
                $updateQuery = $updatePassword 
                    ? "UPDATE Doctorlogin SET username = ?, email = ?, specialization = ?, password = ? WHERE username = ?" 
                    : "UPDATE Doctorlogin SET username = ?, email = ?, specialization = ? WHERE username = ?";
                
                $_SESSION["specialization"] = $specialization;
            } else {
                $age = trim($_POST["age"]);
                if (!is_numeric($age) || $age < 1 || $age > 120) {
                    $message = "<span style='color:red;'>Invalid age entered!</span>";
                } else {
                    $updateQuery = $updatePassword 
                        ? "UPDATE Patientlogin SET username = ?, email = ?, age = ?, password = ? WHERE username = ?" 
                        : "UPDATE Patientlogin SET username = ?, email = ?, age = ? WHERE username = ?";
                    
                    $_SESSION["age"] = $age;
                }
            }

            if (empty($message)) {
                $stmt = mysqli_prepare($conn, $updateQuery);
                if ($user_type == "Doctor") {
                    $updatePassword 
                        ? mysqli_stmt_bind_param($stmt, "sssss", $new_username, $email, $specialization, $hashed_password, $old_username)
                        : mysqli_stmt_bind_param($stmt, "ssss", $new_username, $email, $specialization, $old_username);
                } else {
                    $updatePassword 
                        ? mysqli_stmt_bind_param($stmt, "ssiss", $new_username, $email, $age, $hashed_password, $old_username)
                        : mysqli_stmt_bind_param($stmt, "ssis", $new_username, $email, $age, $old_username);
                }

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION["username"] = $new_username;
                    $_SESSION["email"] = $email;
                    $message = "<span style='color:green;'>Profile updated successfully!</span>";
                } else {
                    $message = "<span style='color:red;'>Error updating profile: " . mysqli_error($conn) . "</span>";
                }
            }
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
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
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
            transition: 0.3s;
        }
        button:hover {
            background: #218838;
        }
        .message {
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <div class="message"><?php echo $message; ?></div>
        <form method="POST">
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            
            <?php if ($user_type == "Doctor"): ?>
                <input type="text" name="specialization" value="<?php echo htmlspecialchars($age_or_specialization); ?>" required>
            <?php else: ?>
                <input type="number" name="age" value="<?php echo htmlspecialchars($age_or_specialization); ?>" required>
            <?php endif; ?>

            <input type="password" name="old_password" placeholder="Old Password (required to change password)">
            <input type="password" name="new_password" placeholder="New Password">
            <input type="password" name="confirm_password" placeholder="Confirm New Password">
            
            <button type="submit">Update Profile</button> <br><br>
			 <a href="profile.php">Go Back</a>
        </form>
    </div>
</body>
</html>
