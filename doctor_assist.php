<?php
session_start();

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all doctor usernames
$query = "SELECT username FROM users WHERE role = 'doctor'";
$result = $conn->query($query);
$doctors = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row['username'];
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Assistance</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
            margin-top: 20px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .doctor-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .doctor-list li {
            padding: 10px;
            font-size: 18px;
            background-color: #e7f1ff;
            color: #333;
            border: 1px solid #007bff;
            border-radius: 4px;
            margin-bottom: 10px;
            width: calc(100% - 20px);
            text-align: center;
            box-sizing: border-box;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
        }
        .doctor-list li:hover {
            background-color: #d0e8ff;
            transform: scale(1.05);
        }
    </style>
    <script>
        function viewTimeSlots(doctor) {
            window.location.href = 'doctor_timeslots.php?doctor=' + encodeURIComponent(doctor);
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Available Doctors</h2>
        <ul class="doctor-list">
            <?php foreach ($doctors as $doctor): ?>
                <li onclick="viewTimeSlots('<?php echo htmlspecialchars($doctor); ?>')"><?php echo htmlspecialchars($doctor); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
