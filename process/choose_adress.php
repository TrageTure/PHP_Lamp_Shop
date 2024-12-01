<?php 
    session_start();
    include_once('../classes/DeliveryLocations.php');
    include_once('../classes/User.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        
        $user = new User();
        $result = $user->getAllFromEmail($_SESSION['email']);
        $userid = $result['id'];

        $location = Deliverylocation::updateActive($id, $userid);

        $result = [
            "status" => "success",
            "message" => "Adress is op actief gezet",
            'id' => $id,
        ];

        echo json_encode($result);
    }
    else{
        $error = [
            "status" => "error",
            "message" => "Adress kon niet op actief worden gezet"
        ];
        echo json_encode($error);
    }
?>