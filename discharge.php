<?php
session_start();
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "doctor") {
    $_SESSION["message"] = "Only doctors can discharge patients.";
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "clinic");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$doctor_email = $_SESSION["email"];
$success_message = "";

// ✅ Fetch doctor ID
$doctor_query = "SELECT id FROM doctorlogin WHERE email = ?";
$stmt_doctor = mysqli_prepare($conn, $doctor_query);
mysqli_stmt_bind_param($stmt_doctor, "s", $doctor_email);
mysqli_stmt_execute($stmt_doctor);
$result_doctor = mysqli_stmt_get_result($stmt_doctor);
$doctor_data = mysqli_fetch_assoc($result_doctor);
mysqli_stmt_close($stmt_doctor);

if (!$doctor_data) {
    die("<p style='color: red;'>Error: Doctor not found.</p>");
}

$doctor_id = $doctor_data["id"];

// ✅ Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = intval($_POST["patient_id"]);
    $discharge_date = $_POST["discharge_date"];
    $treatment = mysqli_real_escape_string($conn, trim($_POST["treatment"]));
    $total_bill = floatval($_POST["total_bill"]);

    // ✅ Verify if the patient was admitted by the logged-in doctor
    $check_query = "SELECT a.patient_id, p.username AS patient_name, a.admission_date, a.disease 
                    FROM Admissions a
                    JOIN patientlogin p ON a.patient_id = p.id
                    WHERE a.patient_id = ? AND a.doctor_email = ?";
    $stmt_check = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt_check, "is", $patient_id, $doctor_email);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    $admission_data = mysqli_fetch_assoc($result_check);
    mysqli_stmt_close($stmt_check);

    if (!$admission_data) {
        die("<script>alert('Error: You can only discharge patients that you admitted.'); window.location='discharge.php';</script>");
    }

    $patient_name = $admission_data["patient_name"];
    $admission_date = $admission_data["admission_date"];
    $disease = $admission_data["disease"];

    // ✅ Insert into discharges table
    $query = "INSERT INTO discharges (patient_id, doctor_id, admission_date, discharge_date, treatment, total_bill, disease, doctor_email) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iisssdss", $patient_id, $doctor_id, $admission_date, $discharge_date, $treatment, $total_bill, $disease, $doctor_email);

    if (mysqli_stmt_execute($stmt)) {
        // ✅ Remove patient from Admissions
        $delete_query = "DELETE FROM Admissions WHERE patient_id = ?";
        $stmt_delete = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt_delete, "i", $patient_id);
        mysqli_stmt_execute($stmt_delete);
        mysqli_stmt_close($stmt_delete);

        echo "<script>alert('Discharge successful!'); window.location='discharage_dashboard.php';</script>";
    } else {
        die("<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>");
    }
    mysqli_stmt_close($stmt);
}

// ✅ Fetch only admitted patients by the logged-in doctor
$patients = mysqli_query($conn, "SELECT a.patient_id, p.username AS patient_name 
                                 FROM Admissions a
                                 JOIN patientlogin p ON a.patient_id = p.id
                                 WHERE a.doctor_email = '$doctor_email'");
if (!$patients) {
    die("<p style='color: red;'>Query Failed: " . mysqli_error($conn) . "</p>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Discharge Patient</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
           padding: 0;
           background: url('12.jpg') no-repeat center center fixed;
           background-size: cover;
           height: 100vh;
           width: 100vw;
        }
        .container {
            width: 30%;
            background: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
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
    <h1>Discharge Patient</h1>

    <form method="POST">
        <label>Select Patient</label>
        <select name="patient_id" required>
            <option value="">-- Select Patient --</option>
            <?php while ($patient = mysqli_fetch_assoc($patients)) { ?>
                <option value="<?php echo $patient['patient_id']; ?>">
                    <?php echo htmlspecialchars($patient['patient_name']); ?>
                </option>
            <?php } ?>
        </select>

        <label>Discharge Date</label>
        <input type="date" name="discharge_date" required>

        <label>Treatment Details</label>
        <textarea name="treatment" placeholder="Treatment Details" required></textarea>

        <label>Total Bill (₹)</label>
        <input type="number" name="total_bill" placeholder="Enter bill amount" step="0.01" required>

        <button type="submit">Discharge Patient</button>
    </form>

    <p><b><a href="index.php">🏠 Go To Home</a></b></p>
    <p><b><a href="discharage_dashboard.php">View Discharge Records</a></b></p>
    <p><b><a href="admission.php">🏥Admit New Patient</a></b></p>
</div>
</body>
</html>
