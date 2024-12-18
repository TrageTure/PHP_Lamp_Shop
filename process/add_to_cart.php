<?php
session_start();
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../classes/Product.php');
include_once('../classes/ProductOptions.php');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['product_id'], $data['color_id'], $data['size_id'], $data['amount'])) {
    $product_id = $data['product_id'];
    $color_id = $data['color_id'];
    $size_id = $data['size_id'];
    $amount = $data['amount'];

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
        $_SESSION['cart'] = []; 
    }

    $product = new Product();
    $product->getProductById($product_id);

    $productOptions = new Options();
    $price = $productOptions->getPriceByColorAndSize($product_id, $color_id, $size_id);
    $stock = $productOptions->getStockAmountByColorAndSize($product_id, $color_id, $size_id);
    $stock = $stock['stock_amount'];

    if ($stock === null) {
        throw new Exception('Stock not found for the given product, color, and size');
    }

    $itemExists = false;
    foreach ($_SESSION['cart'] as $index => $cartItem) {
        if ($cartItem['product_id'] == $product_id && $cartItem['color_id'] == $color_id && $cartItem['size_id'] == $size_id) {
            if ($cartItem['amount'] + $amount > $stock) {
                throw new Exception('Not enough stock available');
            } else {
                $_SESSION['cart'][$index]['amount'] += $amount;
                $itemExists = true;
                break;
            }
        }
    }

    if (!$itemExists) {
        if ($amount > $stock) {
            throw new Exception('Not enough stock available');
        }
        $newItem = [
            'product_id' => $product_id,
            'name' => $product->getTitle(),
            'price' => $price,
            'amount' => $amount,
            'color_id' => $color_id,
            'size_id' => $size_id,
        ];

        $_SESSION['cart'][] = $newItem; 
    }
}
?>