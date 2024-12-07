<?php
session_start();
include_once('../classes/Reviews.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $product_id = $input['product_id'] ?? null;
    $limit = $input['limit'] ?? 6;
    $offset = $input['offset'] ?? 0;

    if (!$product_id) {
        echo json_encode(['success' => false, 'message' => $product_id]);
        exit;
    }

    $reviews = Review::getReviewsByProductId($product_id, (int)$limit, (int)$offset);

    echo json_encode(['success' => true, 'reviews' => $reviews]);
    exit;
}