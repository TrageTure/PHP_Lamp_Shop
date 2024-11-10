<?php
session_start();
header('Content-Type: application/json');

if (isset($_GET['index'])) {
    $index = (int)$_GET['index']; // Zorg ervoor dat het een integer is

    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]); // Verwijder het item uit de sessie

        // Herindexeer de array om gaten in de indexen te voorkomen
        $_SESSION['cart'] = array_values($_SESSION['cart']);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Item niet gevonden.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Ongeldige parameters.']);
}
?>