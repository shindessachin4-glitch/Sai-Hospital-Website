<?php
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

// Store the message and then clear it so it's not displayed again on refresh
$login_message = isset($_SESSION["login_success"]) ? $_SESSION["login_success"] : "";
unset($_SESSION["login_success"]); // Remove message after displaying

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styling1.css">
	<style>
	
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
    padding: 50px;
    border-radius: 5px;
    box-shadow: 0px 0px 100px rgba(0, 0, 0, 0.1);
    text-align: center;
	background:white;
}
.bu{
  background:red;
    color: white;
    border: none;
    cursor: pointer;
	border: none;
	 border-radius: 12px;
    
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

	</style>
</head>
<body>


<div class="container">
<h1>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
 <!-- Show login success message if exists -->
<?php if (!empty($login_message)): ?>
    <p style="color: green; font-weight: bold;"><?php echo $login_message; ?></p>
<?php endif; ?>
    <p><a href="logout.php"><button class="bu">Logout</button></a></p>
 <p><a href="index.php">Go TO Home</a></p>

 </div>
</body>
</html>
