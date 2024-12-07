<?php
session_start();

include_once('../classes/Order.php');
include_once('../classes/Orderline.php');
include_once('../classes/User.php');

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../views/login.php');
    exit;
}

// Controleer of het formulier correct is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Haal de benodigde gegevens op uit de sessie
        $user = new User();
        $user_data = $user->getAllFromEmail($_SESSION['email']);
        $user_id = $user_data['id'] ?? null;
        $balance = $user_data['currency'] ?? 0;

        $delivery_option = $_SESSION['delivery'] ?? 0; // Standard = 0
        $delivery_location = $_SESSION['delivery_location'] ?? null;
        $cart = $_SESSION['cart'] ?? [];
        $total_price = $_SESSION['total_price'] ?? 0.0;

        // Debug ontbrekende gegevens
        if (!$user_id || !$delivery_location || empty($cart) || $total_price <= 0) {
            echo "<pre>";
            echo "Debugging ontbrekende waarden:\n";
            echo "User ID: " . var_export($user_id, true) . "\n";
            echo "Delivery Option: " . var_export($delivery_option, true) . "\n";
            echo "Delivery Location: " . var_export($delivery_location, true) . "\n";
            echo "Cart: " . var_export($cart, true) . "\n";
            echo "Total Price: " . var_export($total_price, true) . "\n";
            echo "</pre>";
            throw new Exception("Sessiegegevens zijn incompleet. Probeer opnieuw.");
        }

        // Controleer het saldo
        if ($balance < $total_price) {
            throw new Exception("Onvoldoende saldo om de bestelling te plaatsen.");
        }

        // Maak een nieuwe bestelling aan
        $order = new Order(null, $user_id, null, $total_price, $delivery_option, $delivery_location);
        $order_id = $order->createOrder($user_id, $total_price, $delivery_option, $delivery_location);

        // Voeg alle orderregels toe
        foreach ($cart as $item) {
            $orderline = new Orderline(null, $order_id, $item['product_id'], $item['amount'], $item['price']);
            $orderline->save();
        }

        // Werk het saldo van de gebruiker bij
        $new_balance = $balance - $total_price;
        $user->updateBalance($user_id, $new_balance);

        // Succesvolle respons
        echo json_encode(['success' => true, 'message' => 'Bestelling succesvol geplaatst.']);
        // Wis de sessiegegevens
        unset($_SESSION['cart'], $_SESSION['delivery_option'], $_SESSION['delivery_location'], $_SESSION['total_price']);

        exit;

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
} else {
    echo "Ongeldige aanvraag.";
    exit;
}
?>

