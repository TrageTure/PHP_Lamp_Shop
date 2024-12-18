<?php
session_start();
header('Content-Type: application/json');

if ($_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Niet ingelogd']);
    exit;
}

$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);
$totalPrice = $data['total_price'] ?? null;

$delivery = $data['delivery'];
if ($delivery ="standard") {
    $deliveryId = 0;
}elseif ($delivery ="express") {
    $deliveryId = 1;
}
$_SESSION['delivery'] = $deliveryId;
$_SESSION['total_price'] = $totalPrice;

$result = ['status' => true,
'message' => 'Delivery set',
'value' => $delivery,];

echo json_encode($result);

?>