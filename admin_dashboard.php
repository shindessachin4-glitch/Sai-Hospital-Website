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

// Count total admissions
$admission_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM admissions");
$admission_data = mysqli_fetch_assoc($admission_query);
$total_admissions = $admission_data['total'];

// Count total patients
$patient_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Patientlogin");
$patient_data = mysqli_fetch_assoc($patient_query);
$total_patients = $patient_data['total'];

// Count total doctors
$doctor_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM doctorlogin"); 
$doctor_data = mysqli_fetch_assoc($doctor_query);
$total_doctors = $doctor_data['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            min-height: 100vh;
            color: white;
        }

        /* Sidebar Navigation */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            padding: 20px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            box-shadow: 3px 0px 10px rgba(0, 0, 0, 0.3);
            transition: width 0.3s ease;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 22px;
            color: #ecf0f1;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .sidebar a:hover {
            background: #34495e;
            transform: scale(1.05);
        }

        .logout {
            background: #e74c3c;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 270px;
            padding: 30px;
            width: calc(100% - 270px);
            transition: margin-left 0.3s ease;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .dashboard-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .dashboard-header p {
            font-size: 16px;
            opacity: 0.8;
        }

        /* Dashboard Cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            color: #2c3e50;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        .card h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .card p {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 210px;
            }

            .cards {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .sidebar a {
                text-align: center;
                font-size: 14px;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }

            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar Navigation -->+
<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin_dashboard.php">🏠 Dashboard</a>
    <a href="appointment_list.php">📅 Appointments</a>
    <a href="admin_prescription.php">📝 Prescriptions</a>
    <a href="admission_list.php">📋 Admissions</a>
    <a href="admin_discharge_dashboard.php">🏥 Discharge</a>
    <a href="patient_list.php">🧑 Patients</a>
    <a href="doctor_list.php">👨‍⚕️ Doctors</a>
    <a href="add_doctor.php">➕ Add Doctor</a>
    <a href="remove_doctor.php">➖ Remove Doctor</a>
	<a href="index.php">🏠Go To Home</a>
	<a href="admin-contact-messages.php">📬 View Contact Messages</a>

    <a href="admin_logout.php" class="logout">🚪 Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="dashboard-header">
        <h1>Welcome, Admin</h1>
        <p>Manage all operations from this panel.</p>
    </div>

    <!-- Dashboard Cards -->
    <div class="cards">
        <div class="card">
            <h3>🏥 Total Admissions</h3>
            <p><?php echo $total_admissions; ?></p>
        </div>
        <div class="card">
            <h3>🧑‍ Total Patients</h3>
            <p><?php echo $total_patients; ?></p>
        </div>
        <div class="card">
            <h3>👨‍⚕️ Total Doctors</h3>
            <p><?php echo $total_doctors; ?></p>
        </div>
    </div>
</div>

</body>
</html>
