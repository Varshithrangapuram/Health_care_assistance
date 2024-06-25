<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch interaction history
$history_query = "SELECT * FROM interaction_history ORDER BY id DESC LIMIT 10"; // Limit to last 10 interactions
$history_result = $conn->query($history_query);

if ($history_result && $history_result->num_rows > 0) {
    while ($row = $history_result->fetch_assoc()) {
        echo '<div class="history-item">';
        echo '<p><strong>Input Symptoms:</strong> ' . htmlspecialchars($row['symptoms']) . '</p>';
        echo '<p><strong>Predicted Disease:</strong> ' . htmlspecialchars($row['predicted_disease']) . '</p>';
        echo '<p><strong>Cure:</strong> ' . htmlspecialchars($row['cure']) . '</p>';
        echo '</div>';
    }
} else {
    echo "No history found";
}

$conn->close();
?>
