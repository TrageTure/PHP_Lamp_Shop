<?php
session_start(); // Start de sessie bovenaan het bestand

include_once('../classes/Product.php');
include_once('../classes/ProductOptions.php');

if (isset($_GET['product_id'], $_GET['color_id'], $_GET['size_id'])) {
    $productId = $_GET['product_id'];
    $colorId = $_GET['color_id'];
    $sizeId = $_GET['size_id'];

    $options = new Options();
    $stock = $options->getStockAmountByColorAndSize($productId, $colorId, $sizeId);
    $price = $options->getPriceByColorAndSize($productId, $colorId, $sizeId);

    echo json_encode(['stock_amount' => $stock['stock_amount'], 'price' => $price]);
    exit;
}