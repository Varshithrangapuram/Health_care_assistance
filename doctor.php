<?php
session_start();

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit;
}

// Handle form submission for shift selection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['shift'])) {
        $selectedShift = $_POST['shift'];
        if ($selectedShift === 'morning') {
            header("Location: mrngshift.php");
            exit;
        } elseif ($selectedShift === 'night') {
            header("Location: nightshift.php");
            exit;
        }
    }
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
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
            position: relative; /* For positioning the notification button */
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .shift-buttons {
            display: flex;
            flex-direction: column; /* Display buttons vertically */
            align-items: center; /* Center align buttons horizontally */
        }
        .shift-buttons form {
            margin-bottom: 10px; /* Add margin between buttons */
        }
        .shift-buttons button {
            padding: 12px 20px;
            font-size: 18px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
        }
        .shift-buttons button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .notification-button {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
        }
        .notification-button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="data_dashboard.php" class="notification-button">Notifications</a>
        <h2>Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Doctor'; ?></h2>
        <div class="shift-buttons">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="shift" value="morning">
                <button type="submit">Morning Shift (10am - 7pm)</button>
            </form>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="shift" value="night">
                <button type="submit">Night Shift (6pm - 5am)</button>
            </form>
        </div>
    </div>
</body>
</html>
