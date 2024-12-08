<?php
session_start();
if ($_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Niet ingelogd']);
    exit;
}

include_once('../classes/Product.php');
include_once('../classes/Pictures.php');

if (!isset($_POST['search'])) {
    echo json_encode(['success' => false, 'message' => 'Geen zoekterm ingevuld']);
    exit;
} else {
    $product = new Product();
    $products = $product->searchProducts($_POST['search']);
    $images = new Images();
    foreach ($products as $key => $product) {
        $thumbnail = $images->getThumbnailByProductId($product['id']);
        $products[$key]['thumbnail'] = $thumbnail;
    }

    echo json_encode(['success' => true, 'products' => $products]);
    exit;
}