<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Je moet ingelogd zijn om een adres te bewerken.']);
    exit;
}

include_once('../classes/DeliveryLocations.php');
include_once('../classes/User.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Haal de gebruikersgegevens op
        $user = new User();
        $result = $user->getAllFromEmail($_SESSION['email']);
        $user_id = $result['id'];

        $id = $_POST['id'];
        $adress_naam = $_POST['name'];
        $street = $_POST['street'];
        $number = $_POST['house_number'];
        $postal_code = $_POST['postal_code'];
        $city = $_POST['city'];
        $country = $_POST['country'];

        if (!$id || !$adress_naam || !$street || !$number || !$postal_code || !$city || !$country) {
            echo json_encode(['status' => 'error', 'message' => 'Alle velden zijn verplicht.']);
            exit;
        }

        // Update de gegevens in de database
        $result = Deliverylocation::editDeliveryLocation($id, $user_id, $street, $number, $city, $postal_code, $country, $adress_naam);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Adres succesvol bijgewerkt.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Adres kon niet worden bijgewerkt.']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Fout: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ongeldige aanvraag.']);
}
?>