<?php
session_start();
header('Content-Type: application/json');

if ($_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Niet ingelogd']);
    exit;
}

$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);

if (!isset($data['delivery_id'])) {
    echo json_encode(['success' => false, 'message' => 'Geen aflevermethode ontvangen']);
    exit;
}

$deliveryId = $data['delivery_id'];
$_SESSION['delivery_id'] = $deliveryId;
echo json_encode(['success' => true, 'message' => 'Aflevermethode opgeslagen']);
?>