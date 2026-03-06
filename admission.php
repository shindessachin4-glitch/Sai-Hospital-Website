<?php
session_start();
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "doctor") {
    $_SESSION["message"] = "Only doctors can admit patients.";
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "clinic");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_name = mysqli_real_escape_string($conn, $_POST["patient_name"]);
    $age = mysqli_real_escape_string($conn, $_POST["age"]);
    $gender = mysqli_real_escape_string($conn, $_POST["gender"]);
    $contact = mysqli_real_escape_string($conn, $_POST["contact"]);
    $address = mysqli_real_escape_string($conn, $_POST["address"]);
    $disease = mysqli_real_escape_string($conn, $_POST["disease"]);
    $bed_number = mysqli_real_escape_string($conn, $_POST["bed_number"]);
    $doctor_email = $_SESSION["email"];

    // ✅ Check if patient exists
    $patient_query = "SELECT id FROM patientlogin WHERE username = ?";
    $stmt_patient = mysqli_prepare($conn, $patient_query);
    mysqli_stmt_bind_param($stmt_patient, "s", $patient_name);
    mysqli_stmt_execute($stmt_patient);
    $result_patient = mysqli_stmt_get_result($stmt_patient);
    $patient_data = mysqli_fetch_assoc($result_patient);
    mysqli_stmt_close($stmt_patient);

    if (!$patient_data) {
        echo "<script>alert('Patient not found. Please register first!'); window.location='admission.php';</script>";
        exit();
    }

    $patient_id = $patient_data["id"];

    // ✅ Check if patient is already admitted & fetch the assigned doctor
    $check_query = "SELECT a.id, d.username AS doctor_name FROM Admissions a 
                    JOIN doctorlogin d ON a.doctor_email = d.email 
                    WHERE a.patient_id = ?";
    $stmt_check = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt_check, "i", $patient_id);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    $admission_data = mysqli_fetch_assoc($result_check);

    if ($admission_data) {
        $doctor_name = $admission_data['doctor_name'] ? $admission_data['doctor_name'] : "Unknown Doctor";
        echo "<script>alert('Patient is already admitted by Dr. $doctor_name. Discharge first before readmission.'); window.location='admission.php';</script>";
        exit();
    }

    mysqli_stmt_close($stmt_check);

    // ✅ Check if bed is occupied
    $bed_check_query = "SELECT id FROM Admissions WHERE bed_number = ?";
    $stmt_bed_check = mysqli_prepare($conn, $bed_check_query);
    mysqli_stmt_bind_param($stmt_bed_check, "s", $bed_number);
    mysqli_stmt_execute($stmt_bed_check);
    $result_bed = mysqli_stmt_get_result($stmt_bed_check);

    if (mysqli_num_rows($result_bed) > 0) {
        echo "<script>alert('Bed already occupied. Please choose another bed.'); window.location='admission.php';</script>";
        exit();
    }

    mysqli_stmt_close($stmt_bed_check);

    // ✅ Insert into Admissions table
    $query = "INSERT INTO Admissions (patient_id, patient_name, age, gender, contact, address, disease, bed_number, doctor_email) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isissssss", $patient_id, $patient_name, $age, $gender, $contact, $address, $disease, $bed_number, $doctor_email);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Patient admitted successfully!'); window.location='dashboard.php';</script>";
    } else {
        echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admit Patient</title>
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
		 h2
		{
			text-align: center; 
			margin-bottom: 5px; 
			}
        .container {
            width: 30%;
			height: 100%;
            background: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        input, select, button, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
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
    <h2>Admit New Patient</h2>
    <form method="POST">
        <input type="text" name="patient_name" placeholder="Patient Name" required>
        <input type="number" name="age" placeholder="Age" required>
        <p>Gender:</p>
        <select name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <input type="text" name="contact" placeholder="Contact" required>
        <textarea name="address" placeholder="Address"></textarea>
        <input type="text" name="disease" placeholder="Disease" required>
        <input type="text" name="bed_number" placeholder="Bed Number" required>
        <button type="submit">Admit Patient</button>
        <p><b><a href="index.php"> 🏠Go To Home</a></b></p>
        <p><b><a href="dashboard.php">View Admitted Patients</a></b></p>
        <p><b><a href="discharge.php">Discharge Patients</a></b></p>
    </form>
</div>

</body>
</html>
