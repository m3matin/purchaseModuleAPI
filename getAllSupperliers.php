<?php
include 'databaseConnection.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
header('Content-Type: application/json');

$sql = "SELECT id, name FROM suppliers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $suppliers = [];
    while ($row = $result->fetch_assoc()) {
        $suppliers[] = $row;
    }
    echo json_encode(["status" => true, "suppliers" => $suppliers]);
} else {
    echo json_encode(["status" => false, "message" => "No suppliers found."]);
}
$conn->close();
