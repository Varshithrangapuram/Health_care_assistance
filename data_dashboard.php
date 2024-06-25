<?php
session_start();

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch appointment requests for the logged-in doctor
$doctor = $_SESSION['username'];
$query = "SELECT id, patient_name, time_slot FROM appointment_requests WHERE doctor_name = ? AND status = 'Pending'";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $doctor);
$stmt->execute();
$result = $stmt->get_result();

$requests = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}
$stmt->close();
$conn->close();

// Handle form submission for accepting or rejecting requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requestId = $_POST['request_id'];
    $action = $_POST['action'];

    $conn = new mysqli("localhost", "root", "", "db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update appointment status
    $status = $action === 'accept' ? 'Accepted' : 'Rejected';
    $stmt = $conn->prepare("UPDATE appointment_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $requestId);
    $stmt->execute();

    if ($action === 'accept') {
        // Fetch the time slot of the accepted request
        $stmt = $conn->prepare("SELECT time_slot FROM appointment_requests WHERE id = ?");
        $stmt->bind_param("i", $requestId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $timeSlot = $row['time_slot'];
        $stmt->close();

        // Delete the corresponding time slot from the doctor_details table
        $stmt = $conn->prepare("DELETE FROM doctor_details WHERE doctor_name = ? AND time_slot = ?");
        $stmt->bind_param("ss", $doctor, $timeSlot);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    header("Location: data_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
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
        .request-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .request-list li {
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
        }
        .request-list form {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .request-list button {
            padding: 8px 16px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
        }
        .request-list button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .request-list .reject-button {
            background-color: #dc3545;
        }
        .request-list .reject-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pending Appointment Requests</h2>
        <ul class="request-list">
            <?php foreach ($requests as $request): ?>
                <li>
                    <div><?php echo htmlspecialchars($request['patient_name']); ?> requested <?php echo htmlspecialchars($request['time_slot']); ?></div>
                    <form method="post" action="data_dashboard.php">
                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                        <button type="submit" name="action" value="accept">Accept</button>
                        <button type="submit" name="action" value="reject" class="reject-button">Reject</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
