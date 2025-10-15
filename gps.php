<?php
// gps.php
// Connect to Render PostgreSQL
$DATABASE_URL = getenv("DATABASE_URL");
if (!$DATABASE_URL) {
  $DATABASE_URL = "postgresql://gps_tracker_db_0tv9_user:jGlU2ps2qat54sOcGUU9zqN46uXvWPKy@dpg-d3njg9s9c44c73eapbng-a/gps_tracker_db_0tv9";
}

$parts = parse_url($DATABASE_URL);
$conn = pg_connect("host={$parts['host']} dbname=" . ltrim($parts['path'], '/') . " user={$parts['user']} password={$parts['pass']}");

if (!$conn) {
  die("Database connection failed");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $lat = $_POST['lat'] ?? '';
  $lng = $_POST['lng'] ?? '';
  $time = date('Y-m-d H:i:s');

  if ($lat && $lng) {
    $query = "INSERT INTO gps_data (latitude, longitude, timestamp) VALUES ($1, $2, $3)";
    $result = pg_query_params($conn, $query, array($lat, $lng, $time));
    if ($result) {
      echo "Data inserted successfully!";
    } else {
      echo "Failed to insert data.";
    }
  } else {
    echo "Missing parameters.";
  }
} else {
  echo "GPS API is running!";
}
?>
