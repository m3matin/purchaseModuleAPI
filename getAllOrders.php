<?php
include 'databaseConnection.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Query to get all purchase orders with supplier name
$sql = "SELECT po.id, po.supplier_id, po.grand_total, s.name as supplier_name 
        FROM purchase_orders po
        INNER JOIN suppliers s ON po.supplier_id = s.id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    echo json_encode(["status" => true, "orders" => $orders]);
} else {
    echo json_encode(["status" => false, "message" => "No orders found."]);
}

$conn->close();
