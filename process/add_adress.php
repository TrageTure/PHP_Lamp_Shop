<?php
    session_start();
    if ($_SESSION['loggedin'] !== true) {
        header('location: login.php');
    }
    include_once('../classes/Product.php');
    include_once('../classes/Pictures.php');
    include_once('../classes/ProductOptions.php');
    include_once('../classes/Order.php');
    include_once('../classes/User.php');
    include_once('../classes/DeliveryLocations.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $street = $_POST['street_name'];
        $number = $_POST['house_number'];
        $city = $_POST['city'];
        $postal_code = $_POST['postal_code'];
        $country = $_POST['country'];
        $adress_naam = $_POST['adress_naam'];

        $user = new User();
        $result = $user->getAllFromEmail($_SESSION['email']);
        $user_id = $result['id'];

        $location = new Deliverylocation($user_id, $street, $number, $city, $postal_code, $country, 0, $adress_naam);
        $location->save();

        $result = [
            "naam" => $adress_naam,
            "status" => "success",
            "message" => `Adress is toegevoegd`
        ];

        echo json_encode($result);
    }
?>