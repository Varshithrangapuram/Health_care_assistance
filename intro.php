<?php
session_start();

// Check if user is logged in and role is set in session
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit;
}

// Get the role of the logged-in user
$role = $_SESSION['role'];

// Redirect based on role
if ($role === 'doctor') {
    header('Location: doctor.php');
    exit;
} elseif ($role === 'patient') {
    header('Location: patient.php');
    exit;
} else {
    // Handle any other roles as needed
    header('Location: error.php'); // Redirect to an error page if role is unexpected
    exit;
}
?>
