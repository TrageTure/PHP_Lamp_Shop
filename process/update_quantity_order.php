<?php
session_start();

header('Content-Type: application/json');
include_once('../classes/ProductOptions.php');

if (!isset($_SESSION['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Winkelwagen is leeg.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['index']) || !isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Ongeldige parameters.']);
    exit;
}

$index = $data['index'];
$action = $data['action'];

if (!isset($_SESSION['cart'][$index])) {
    echo json_encode(['success' => false, 'message' => 'Item niet gevonden in winkelwagen.']);
    exit;
}

$options = new Options();
$stock = $options->getStockAmountByColorAndSize($_SESSION['cart'][$index]['product_id'], $_SESSION['cart'][$index]['color_id'], $_SESSION['cart'][$index]['size_id']);
$stock = $stock['stock_amount'];

if ($action === 'increase') {
    if ($_SESSION['cart'][$index]['amount'] + 1 > $stock) {
        echo json_encode(['success' => false, 'message' => 'Niet genoeg voorraad beschikbaar.']);
        exit;
    }
    $_SESSION['cart'][$index]['amount']++;
} elseif ($action === 'decrease') {
    if ($_SESSION['cart'][$index]['amount'] > 1) {
        $_SESSION['cart'][$index]['amount']--;
    } else {
        unset($_SESSION['cart'][$index]);
        echo json_encode(['success' => true, 'message' => 'Item verwijderd uit winkelwagen.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Ongeldige actie.']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Hoeveelheid succesvol bijgewerkt.']);
?>