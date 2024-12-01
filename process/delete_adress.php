<?php 
    session_start();
    if ($_SESSION['loggedin'] !== true) {
        header('location: login.php');
    }

    include_once('../classes/DeliveryLocations.php');
    include_once('../classes/User.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['addressId'];

        $user = new User();
        $result = $user->getAllFromEmail($_SESSION['email']);
        $userid = $result['id'];

        $location = Deliverylocation::deleteAdress($id, $userid);

        $result = [
            "status" => "success",
            "message" => "Adress is verwijderd"
        ];

        echo json_encode($result);
    }
    else{
        $error = [
            "status" => "error",
            "message" => "Adress kon niet worden verwijderd"
        ];
        echo json_encode($error);
    }
    ?>