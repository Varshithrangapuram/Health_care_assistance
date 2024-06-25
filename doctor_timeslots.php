<?php
session_start();

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit;
}

$doctor = $_GET['doctor'];

// Database connection
$conn = new mysqli("localhost", "root", "", "db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch time slots for the selected doctor
$query = "SELECT shift_type, time_slot FROM doctor_details WHERE doctor_name = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $doctor);
$stmt->execute();
$result = $stmt->get_result();

$time_slots = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $time_slots[$row['shift_type']][] = $row['time_slot'];
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Time Slots</title>
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
        .time-slot-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .time-slot-list li {
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
        .time-slot-list li:hover {
            background-color: #d0e8ff;
            transform: scale(1.05);
        }
        .shift-heading {
            margin-top: 20px;
            font-size: 20px;
            color: #007bff;
        }
    </style>
    <script>
        function sendRequest(doctor, timeSlot) {
            fetch('send_request.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ doctor: doctor, timeSlot: timeSlot })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Request sent to ' + doctor + ' successfully');
                } else {
                    alert('Failed to send request');
                }
            });
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Time Slots for <?php echo htmlspecialchars($doctor); ?></h2>
        <?php foreach ($time_slots as $shift_type => $slots): ?>
            <div class="shift-heading"><?php echo htmlspecialchars($shift_type); ?></div>
            <ul class="time-slot-list">
                <?php foreach ($slots as $slot): ?>
                    <li onclick="sendRequest('<?php echo htmlspecialchars($doctor); ?>', '<?php echo htmlspecialchars($slot); ?>')"><?php echo htmlspecialchars($slot); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    </div>
</body>
</html>
