<?php
session_start();

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Night Shift</title>
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
            max-width: 400px;
            width: 100%;
            text-align: center;
            margin-top: 20px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .notification-button {
            position: fixed;
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
        .time-slot {
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
    </style>
</head>
<body>
    <button class="notification-button">Notifications</button>
    <div class="container">
        <h2>Night Shift (6pm - 5am)</h2>
        <div class="time-slot">6pm to 7pm</div>
        <div class="time-slot">7pm to 8pm</div>
        <div class="time-slot">8pm to 9pm</div>
        <div class="time-slot">9pm to 10pm</div>
        <div class="time-slot">10pm to 11pm</div>
        <div class="time-slot">11pm to 12am</div>
        <div class="time-slot">12am to 1am</div>
        <div class="time-slot">1am to 2am</div>
        <div class="time-slot">2am to 3am</div>
        <div class="time-slot">3am to 4am</div>
        <div class="time-slot">4am to 5am</div>
    </div>
</body>
</html>
