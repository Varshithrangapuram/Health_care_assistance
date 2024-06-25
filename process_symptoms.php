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

// Create interaction_history table if it does not exist
$create_table_query = "CREATE TABLE IF NOT EXISTS interaction_history (
                        id INT(11) AUTO_INCREMENT PRIMARY KEY,
                        symptoms VARCHAR(255) NOT NULL,
                        predicted_disease VARCHAR(255) NOT NULL,
                        cure TEXT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                      )";

if ($conn->query($create_table_query) === FALSE) {
    echo "Error creating table: " . $conn->error;
}

// Function to predict disease using Python script
function predictDisease($symptoms) {
    $python_path = '/Applications/XAMPP/xamppfiles/htdocs/Health_care_bot/venv/bin/python';
    $symptoms_escaped = escapeshellarg($symptoms);
    $command = "{$python_path} /Applications/XAMPP/xamppfiles/htdocs/Health_care_bot/prediction.py {$symptoms_escaped}";
    $output = shell_exec($command . ' 2>&1');
    return trim($output);
}

// Get symptoms from POST request
if (!empty($_POST['symptoms'])) {
    $symptoms = $_POST['symptoms'];

    // Predict disease
    $predicted_disease = predictDisease($symptoms);

    // Query to fetch cure based on predicted disease
    $query = "SELECT cure FROM disease_cures WHERE disease LIKE '%" . $conn->real_escape_string($predicted_disease) . "%'";
    $result = $conn->query($query);

    $cure = "No cure found"; // Default message if no cure is found

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cure = $row['cure'];
    }

    // Insert interaction into history table
    $insert_query = "INSERT INTO interaction_history (symptoms, predicted_disease, cure) VALUES ('$symptoms', '$predicted_disease', '$cure')";
    if ($conn->query($insert_query) === FALSE) {
        echo "Error inserting record: " . $conn->error;
    }

    $conn->close();

    // Prepare response HTML
    $response = '<p><strong>Input Symptoms:</strong> ' . htmlspecialchars($symptoms) . '</p>';
    $response .= '<div class="response">';
    $response .= '<p><strong>Predicted Disease:</strong> ' . htmlspecialchars($predicted_disease) . '</p>';
    $response .= '<p><strong>Cure:</strong> ' . htmlspecialchars($cure) . '</p>';
    $response .= '</div>';

    // Output response
    echo $response;
} else {
    echo "No symptoms provided";
}
?>
