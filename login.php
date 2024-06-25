<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = new mysqli("localhost", "root", "", "db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create doctor_details table if it does not exist
    $createTableQuery = "
        CREATE TABLE IF NOT EXISTS doctor_details (
            id INT AUTO_INCREMENT PRIMARY KEY,
            doctor_name VARCHAR(255) NOT NULL,
            shift_type VARCHAR(50) NOT NULL,
            time_slot VARCHAR(50) NOT NULL
        )
    ";
    if ($conn->query($createTableQuery) === FALSE) {
        die("Error creating table: " . $conn->error);
    }

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Store user details in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];

            // If the user is a doctor, insert entries into doctor_details
            if ($_SESSION['role'] === 'doctor') {
                // Prepare the statement for inserting into doctor_details
                $stmt = $conn->prepare("INSERT INTO doctor_details (doctor_name, shift_type, time_slot) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $shift_type, $time_slot);

                // Define the shift types and time slots
                $shift_types = ['Morning Shift', 'Night Shift'];
                $time_slots_morning = [
                    '10am - 11am',
                    '11am - 12pm',
                    '12pm - 1pm',
                    '1pm - 2pm',
                    '2pm - 3pm',
                    '3pm - 4pm',
                    '4pm - 5pm',
                    '5pm - 6pm',
                    '6pm - 7pm'
                ];
                $time_slots_night = [
                    '6pm - 7pm',
                    '7pm - 8pm',
                    '8pm - 9pm',
                    '9pm - 10pm',
                    '10pm - 11pm',
                    '11pm - 12am',
                    '12am - 1am',
                    '1am - 2am',
                    '2am - 3am',
                    '3am - 4am',
                    '4am - 5am'
                ];

                // Insert each time slot into the doctor_details table for morning shift
                foreach ($time_slots_morning as $time_slot) {
                    $shift_type = 'Morning Shift';
                    $stmt->execute();
                }

                // Insert each time slot into the doctor_details table for night shift
                foreach ($time_slots_night as $time_slot) {
                    $shift_type = 'Night Shift';
                    $stmt->execute();
                }

                $stmt->close();
                header("Location: doctor.php");
                exit;
            } elseif ($_SESSION['role'] === 'patient') {
                header("Location: patient.php");
                exit;
            } else {
                // Handle any other roles as needed
                header("Location: error.php");
                exit;
            }
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        input[type="text"], input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            padding: 12px 20px;
            font-size: 18px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .register-link {
            margin-top: 15px;
        }
        .register-link a {
            color: #007bff;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post" action="login.php">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" value="Login">
        </form>
        <div class="register-link">
            Don't have an account? <a href="register.php">Register</a>
        </div>
    </div>
</body>
</html>
