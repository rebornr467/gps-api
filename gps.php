<?php
// Database connection details
$host = "dpg-d3njg9s9c44c73eapbng-a"; // from your URL
$port = "5432";
$dbname = "gps_tracker_db_0tv9";
$user = "gps_tracker_db_0tv9_user";
$password = "jGlU2ps2qat54sOcGUU9zqN46uXvWPKy";

// Connect to PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Check if we received data
if (isset($_GET['lat']) && isset($_GET['lon'])) {
    $lat = $_GET['lat'];
    $lon = $_GET['lon'];

    // Insert into database
    $query = "INSERT INTO gps_data (latitude, longitude) VALUES ($1, $2)";
    $result = pg_query_params($conn, $query, array($lat, $lon));

    if ($result) {
        echo "✅ Data inserted successfully!";
    } else {
        echo "❌ Error inserting data: " . pg_last_error($conn);
    }
} else {
    echo "No GPS data received!";
}

pg_close($conn);
?>
