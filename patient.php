<?php
session_start();

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
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
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .assistance-buttons {
            display: flex;
            flex-direction: column; /* Display buttons vertically */
            align-items: center; /* Center align buttons horizontally */
        }
        .assistance-buttons form {
            margin-bottom: 10px; /* Add margin between buttons */
        }
        .assistance-buttons button {
            padding: 12px 20px;
            font-size: 18px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
        }
        .assistance-buttons button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Patient'; ?></h2>
        <div class="assistance-buttons">
            <form method="post" action="index.php">
                <button type="submit">Self Assistance</button>
            </form>
            <form method="post" action="doctor_assist.php">
                <button type="submit">Doctor Assistance</button>
            </form>
        </div>
    </div>
</body>
</html>
