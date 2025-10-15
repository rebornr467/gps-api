<?php
// Database connection info from your Render PostgreSQL
$db_url = "pgsql:host=dpg-d3njg9s9c44c73eapbng-a;port=5432;dbname=gps_tracker_db_0tv9";
$db_user = "gps_tracker_db_0tv9_user";
$db_pass = "jGlU2ps2qat54sOcGUU9zqN46uXvWPKy";

try {
    $pdo = new PDO($db_url, $db_user, $db_pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}

// Create table if not exists
$pdo->exec("
    CREATE TABLE IF NOT EXISTS gps_data (
        id SERIAL PRIMARY KEY,
        latitude DOUBLE PRECISION,
        longitude DOUBLE PRECISION,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['latitude']) && isset($data['longitude'])) {
    $stmt = $pdo->prepare("INSERT INTO gps_data (latitude, longitude) VALUES (?, ?)");
    $stmt->execute([$data['latitude'], $data['longitude']]);
    echo json_encode(["status" => "success", "message" => "Location saved!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
}
?>