<?php
session_start();
header('Content-Type: application/json');

if ($_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Niet ingelogd']);
    exit;
}

$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);

$data['delivery_id'] = $data['delivery_id'] ?? null;

if ($data['delivery_id'] === null) {
    echo json_encode(['success' => false, 'message' => 'Geen leveringsoptie geselecteerd']);
    exit;
}

$delivery = $data['delivery_id'];

$result = ['success' => true,
'message' => 'Delivery set',
'value' => $delivery,];

echo json_encode($result);
?>