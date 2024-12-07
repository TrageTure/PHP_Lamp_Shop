<?php
session_start();
include_once('../classes/Product.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = new Product();
    try {
        $product->deleteProduct($_POST['product_id']);
        echo "Product verwijderd!";
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

$productInstance = new Product();
$products = $productInstance->getAllProducts();

header('Location: ../views/admin_products.php');
?>