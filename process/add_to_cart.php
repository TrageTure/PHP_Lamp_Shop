<?php
session_start();
header('Content-Type: application/json');

// Foutweergave inschakelen voor debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../classes/Product.php');
include_once('../classes/ProductOptions.php');

// Ontvang de JSON-data van de fetch-aanvraag
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['product_id'], $data['color_id'], $data['size_id'], $data['amount'])) {
    $product_id = $data['product_id'];
    $color_id = $data['color_id'];
    $size_id = $data['size_id'];
    $amount = $data['amount'];

    error_log("Received POST data: product_id=$product_id, color_id=$color_id, size_id=$size_id, amount=$amount");

    try {
        addToCart($product_id, $amount, $color_id, $size_id);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

function addToCart($product_id, $amount, $color_id, $size_id) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Initialiseer de winkelwagen
    }

    // Maak een nieuw Product-object aan en haal de productgegevens op
    $product = new Product();
    $product->getProductById($product_id);

    // Gebruik ProductOptions om de prijs te verkrijgen
    $productOptions = new Options();
    $price = $productOptions->getPriceByColorAndSize($product_id, $color_id, $size_id);

    // Controleer of het product al in de winkelwagen zit met dezelfde kleur en maat
    $itemExists = false;
    foreach ($_SESSION['cart'] as $index => $cartItem) {
        if ($cartItem['product_id'] == $product_id && $cartItem['color_id'] == $color_id && $cartItem['size_id'] == $size_id) {
            // Verhoog de hoeveelheid als het product al in de winkelwagen zit
            $_SESSION['cart'][$index]['amount'] += $amount;
            $itemExists = true;
            break;
        }
    }

    // Voeg een nieuw item toe aan de winkelwagen als het nog niet bestaat
    if (!$itemExists) {
        $newItem = [
            'product_id' => $product_id,
            'name' => $product->getTitle(),
            'price' => $price,
            'amount' => $amount,
            'color_id' => $color_id,
            'size_id' => $size_id
        ];

        $_SESSION['cart'][] = $newItem; // Voeg het nieuwe item toe aan de winkelwagen
    }
}