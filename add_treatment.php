<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "clinic");

if (!$conn) {
    die("<p style='color:red;'>Database connection failed: " . mysqli_connect_error() . "</p>");
}

// Check if a doctor is logged in
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "doctor" || !isset($_SESSION["doctor_id"])) {
    echo "<script>alert('Login as Doctor first. Only for Doctors!'); window.location='doctorlogin.php';</script>";
    exit();
}

$doctor_id = $_SESSION["doctor_id"];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $patient_id = intval($_POST["patient_id"]);
    $symptoms = trim($_POST["symptoms"]);
    $disease = trim($_POST["disease"]);
    $treatment = trim($_POST["treatment"]);
    $bill_amount = floatval($_POST["bill_amount"]); // Convert bill to float

    if (!empty($patient_id) && !empty($disease) && !empty($treatment) && !empty($symptoms) && $bill_amount >= 0) {
        // Insert treatment record
        $query = "INSERT INTO treatments (patient_id, doctor_id, symptoms, disease, treatment, bill) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "iisssd", $patient_id, $doctor_id, $symptoms, $disease, $treatment, $bill_amount);
            if (mysqli_stmt_execute($stmt)) {
                // Remove the booked appointment
                $deleteQuery = "DELETE FROM appointments WHERE name = (SELECT username FROM patientlogin WHERE id = ?)";
                $deleteStmt = mysqli_prepare($conn, $deleteQuery);
                mysqli_stmt_bind_param($deleteStmt, "i", $patient_id);
                mysqli_stmt_execute($deleteStmt);
                mysqli_stmt_close($deleteStmt);

                echo "<script>alert('Prescription Added Successfully!'); window.location='treatmentdashboard.php';</script>";
            } else {
                echo "<p style='color:red;'>Error: Could not save treatment. " . mysqli_error($conn) . "</p>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<p style='color:red;'>Database error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<script>alert('Error: All fields are required, and bill must be a valid amount.');</script>";
    }
}

// Fetch only patients with booked appointments assigned to the doctor
$patients_query = "
    SELECT p.id, p.username 
    FROM patientlogin p
    JOIN appointments a ON p.username = a.name
    WHERE a.doctor = (SELECT username FROM doctorlogin WHERE id = ?)
    ORDER BY a.appointment_date ASC";
$stmt = mysqli_prepare($conn, $patients_query);
mysqli_stmt_bind_param($stmt, "i", $doctor_id);
mysqli_stmt_execute($stmt);
$patients = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prescription</title>
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
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        input, select, textarea, button {
            width: 100%;
            margin: 10px 0;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        textarea {
            height: 100px;
            resize: none;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background: #218838;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        .text {
            text-align: center;
            margin-top: 20px;
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
        @media screen and (max-width: 768px) {
            .container {
                max-width: 100%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Prescription</h1>
        <form method="POST">
            <label>Select Patient</label>
            <select name="patient_id" required>
                <option value="">-- Select Patient --</option>
                <?php while ($patient = mysqli_fetch_assoc($patients)) { ?>
                    <option value="<?php echo $patient['id']; ?>">
                        <?php echo htmlspecialchars($patient['username']); ?>
                    </option>
                <?php } ?>
            </select>

            <label>Symptoms</label>
            <input type="text" name="symptoms" placeholder="Enter Symptoms" required>

            <label>Disease</label>
            <input type="text" name="disease" placeholder="Enter Disease" required>

            <label>Treatment Details</label>
            <textarea name="treatment" placeholder="Enter Treatment Details" required></textarea>

            <label>Bill Amount (₹)</label>
            <input type="number" name="bill_amount" placeholder="Enter Bill Amount" required step="0.01" min="0">

            <button type="submit">Save Treatment</button>

            <p class="text">
                <a href="index.php"><b>Go To Home</b></a>
                <a href="treatmentdashboard.php"><b>View Prescription</b></a>
            </p>
        </form>
    </div>
</body>
</html>
