<?php
include 'databaseConnection.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
header('Content-Type: application/json');

$order_id = $_GET['order_id'] ?? null;

if ($order_id) {
    $order_id = $conn->real_escape_string($order_id);

    $sql = "SELECT po.id as order_id, s.name as supplier_name, po.grand_total, pod.product_id, p.name as product_name, pod.quantity, pod.price, pod.gst, pod.total
            FROM purchase_orders po
            INNER JOIN suppliers s ON po.supplier_id = s.id
            INNER JOIN purchase_order_details pod ON po.id = pod.purchase_order_id
            INNER JOIN products p ON pod.product_id = p.id
            WHERE po.id = '$order_id'";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $invoice = [];
        while ($row = $result->fetch_assoc()) {
            $invoice[] = $row;
        }
        echo json_encode(["status" => true, "invoice" => $invoice]);
    } else {
        echo json_encode(["status" => false, "message" => "No order found."]);
    }
} else {
    echo json_encode(["status" => false, "message" => "Order ID is required."]);
}

$conn->close();
