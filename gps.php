<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data) {
        $latitude = $data['latitude'] ?? null;
        $longitude = $data['longitude'] ?? null;

        if ($latitude && $longitude) {
            echo json_encode(["status" => "success", "message" => "GPS data received", "lat" => $latitude, "lon" => $longitude]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid GPS data"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No JSON data received"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Only POST requests allowed"]);
}
?>