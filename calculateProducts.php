<?php
include 'databaseConnection.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['supplier_id']) && isset($data['products']) && is_array($data['products'])) {
    $supplier_id = $data['supplier_id'];
    $products = $data['products'];

    $grand_total = 0;

    foreach ($products as $product) {
        $price = $product['price'];
        $quantity = $product['quantity'];
        $gst_rate = $product['gst_rate'];

        $gst = ($price * $quantity * $gst_rate) / 100;
        $total = ($price * $quantity) + $gst;

        $grand_total += $total;
    }

    $sql = "INSERT INTO purchase_orders (supplier_id, grand_total) VALUES ('$supplier_id', '$grand_total')";
    if ($conn->query($sql) === TRUE) {
        $purchase_order_id = $conn->insert_id;

        foreach ($products as $product) {
            $product_id = $product['id'];
            $quantity = $product['quantity'];
            $price = $product['price'];
            $gst_rate = $product['gst_rate'];

            $gst = ($price * $quantity * $gst_rate) / 100;
            $total = ($price * $quantity) + $gst;

            $sql_detail = "INSERT INTO purchase_order_details (purchase_order_id, product_id, quantity, price, gst, total) 
                           VALUES ('$purchase_order_id', '$product_id', '$quantity', '$price', '$gst', '$total')";
            $conn->query($sql_detail);
        }

        echo json_encode(["status" => true, "message" => "Purchase order created successfully."]);
    } else {
        echo json_encode(["status" => false, "message" => "Failed to create purchase order."]);
    }
} else {
    echo json_encode(["status" => false, "message" => "Invalid data provided."]);
}

$conn->close();
