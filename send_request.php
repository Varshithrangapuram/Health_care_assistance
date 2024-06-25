<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $doctor = $data['doctor'];
    $timeSlot = $data['timeSlot'];
    $patient = $_SESSION['username'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create appointment_requests table if it does not exist
    $createTableQuery = "
        CREATE TABLE IF NOT EXISTS appointment_requests (
            id INT AUTO_INCREMENT PRIMARY KEY,
            patient_name VARCHAR(255) NOT NULL,
            doctor_name VARCHAR(255) NOT NULL,
            time_slot VARCHAR(50) NOT NULL,
            status VARCHAR(50) DEFAULT 'Pending'
        )
    ";
    if ($conn->query($createTableQuery) === FALSE) {
        echo json_encode(['success' => false, 'message' => 'Error creating table']);
        exit;
    }

    // Insert the request into appointment_requests table
    $stmt = $conn->prepare("INSERT INTO appointment_requests (patient_name, doctor_name, time_slot) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $patient, $doctor, $timeSlot);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error inserting request']);
    }

    $stmt->close();
    $conn->close();
}
?>
