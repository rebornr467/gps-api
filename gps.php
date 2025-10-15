<?php
// gps.php - advanced: accepts JSON POST with device_id, latitude, longitude
header('Content-Type: application/json');

// DB credentials from environment (Render will use these)
$host = getenv('DB_HOST') ?: 'dpg-d3njg9s9c44c73eapbng-a';
$port = getenv('DB_PORT') ?: '5432';
$dbname = getenv('DB_NAME') ?: 'gps_tracker_db_0tv9';
$user = getenv('DB_USER') ?: 'gps_tracker_db_0tv9_user';
$pass = getenv('DB_PASS') ?: 'jGlU2ps2qat54sOcGUU9zqN46uXvWPKy';

$dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "DB connection failed", "detail" => $e->getMessage()]);
    exit;
}

// create table if not exists
$pdo->exec("CREATE TABLE IF NOT EXISTS gps_data (
    id SERIAL PRIMARY KEY,
    device_id VARCHAR(128),
    latitude DOUBLE PRECISION NOT NULL,
    longitude DOUBLE PRECISION NOT NULL,
    timestamp TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
)");

// read JSON body
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data) {
    // Fallback: accept form-encoded POST as well
    $device = $_POST['device_id'] ?? ($_GET['device_id'] ?? null);
    $lat = $_POST['lat'] ?? ($_GET['lat'] ?? null);
    $lng = $_POST['lng'] ?? ($_GET['lon'] ?? ($_GET['lng'] ?? null));
} else {
    $device = $data['device_id'] ?? null;
    $lat = $data['latitude'] ?? $data['lat'] ?? null;
    $lng = $data['longitude'] ?? $data['lon'] ?? $data['lng'] ?? null;
}

if ($lat === null || $lng === null) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing latitude or longitude"] );
    exit;
}

// insert safely
try {
    $stmt = $pdo->prepare('INSERT INTO gps_data (device_id, latitude, longitude) VALUES (:device, :lat, :lng)');
    $stmt->execute([':device' => $device, ':lat' => $lat, ':lng' => $lng]);

    echo json_encode(["status" => "success", "message" => "Location saved", "device_id" => $device, "lat" => (float)$lat, "lng" => (float)$lng]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Insert failed", "detail" => $e->getMessage()]);
}
?>