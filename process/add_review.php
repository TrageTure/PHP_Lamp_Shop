<?php
session_start();

include_once('../classes/Reviews.php');
include_once('../classes/User.php');

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Je moet ingelogd zijn om een review te schrijven.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user = new User();
        $result = $user->getAllFromEmail($_SESSION['email']);
        $user_id = $result['id'];
        $review = $_POST['review'];
        $rating = $_POST['rating'];
        $product_id = $_POST['product_id'];

        $name = $result['first_name']." ".$result['last_name'];

        if (!$product_id || !$rating || !$review) {
            throw new Exception("Alle velden zijn verplicht.");
        }

        if (!Review::hasPurchasedProduct($user_id, $product_id)) {
            throw new Exception("Je kunt alleen een review schrijven voor producten die je hebt gekocht.");
        }

        $review_result = new Review($user_id, $product_id, $rating, $review);
        $review_result->save();

        // Succesresponse
        echo json_encode([
            'success' => true,
            'message' => 'Review succesvol toegevoegd.',
            'user_name' => $name,
            'rating' => $rating,
            'review' => $review
        ]);
        exit;
    } catch (Exception $e) {
        // Foutresponse
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Ongeldige aanvraagmethode.']);
}
?>

