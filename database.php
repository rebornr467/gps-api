<?php
// database.php - convenience include to create PDO from env vars
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
    echo json_encode(["status"=>"error","message"=>"DB connection failed","detail"=>$e->getMessage()]);
    exit;
}
?>