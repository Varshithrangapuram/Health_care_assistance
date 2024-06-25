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
$sql = "CREATE TABLE IF NOT EXISTS disease_cures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    disease VARCHAR(100) NOT NULL,
    cure TEXT
)";

if ($conn->query($sql) === TRUE) {
    echo "Table disease_cures created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}
// Array of diseases and their cures
$diseases = array(
    "Psoriasis" => "Topical treatments, light therapy, and medications.",
    "Varicose Veins" => "Compression stockings, exercise, and surgery if necessary.",
    "Typhoid" => "Antibiotics and supportive care.",
    "Chicken pox" => "Symptomatic treatment, antiviral medications, and rest.",
    "Impetigo" => "Topical or oral antibiotics.",
    "Dengue" => "Supportive care, fluids, and rest.",
    "Fungal infection" => "Antifungal medications and topical treatments.",
    "Common Cold" => "Symptomatic relief, rest, and hydration.",
    "Pneumonia" => "Antibiotics and supportive care.",
    "Dimorphic Hemorrhoids" => "Dietary changes, medications, and surgery in severe cases.",
    "Arthritis" => "Medications, physical therapy, and lifestyle changes.",
    "Acne" => "Topical treatments, medications, and lifestyle changes.",
    "Bronchial Asthma" => "Bronchodilators, steroids, and lifestyle management.",
    "Hypertension" => "Medications, lifestyle changes, and dietary modifications.",
    "Migraine" => "Pain-relieving medications, lifestyle changes, and avoidance of triggers.",
    "Cervical spondylosis" => "Physical therapy, medications, and lifestyle adjustments.",
    "Jaundice" => "Treatment of underlying cause, rest, and supportive care.",
    "Malaria" => "Antimalarial medications.",
    "urinary tract infection" => "Antibiotics and increased fluid intake.",
    "allergy" => "Antihistamines, avoidance of allergens, and immunotherapy if needed.",
    "gastroesophageal reflux disease" => "Antacids, medications, and lifestyle changes.",
    "drug reaction" => "Discontinuation of offending drug, supportive care.",
    "peptic ulcer disease" => "Acid-suppressing medications, antibiotics (if H. pylori infection), and lifestyle changes.",
    "diabetes" => "Insulin or oral medications, dietary management, and lifestyle changes."
);

// Insert each disease and cure into the database
foreach ($diseases as $disease => $cure) {
    $sql = "INSERT INTO disease_cures (disease, cure) VALUES ('$disease', '$cure')";

    if ($conn->query($sql) === TRUE) {
        echo "Inserted: $disease - $cure<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
    }
}
$conn->close();

?>

