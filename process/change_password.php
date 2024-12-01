<?php 
session_start();
include_once('../classes/User.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Controleer of de gebruiker is ingelogd
    if (empty($_SESSION['email'])) {
        $error = [
            "status" => "error",
            "message" => "Je moet ingelogd zijn om je wachtwoord te veranderen."
        ];
        echo json_encode($error);
        exit;
    }

    // Haal wachtwoordgegevens op uit het formulier
    $oldPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Controleer of het nieuwe wachtwoord overeenkomt
    if ($newPassword !== $confirmPassword) {
        $error = [
            "status" => "error",
            "message" => "De nieuwe wachtwoorden komen niet overeen.",
            "error_div" => "confirm_password"
        ];
        echo json_encode($error);
        exit;
    }

    try {
        $user = new User();
        $user->setEmail($_SESSION['email']); 

        $result = $user->changePassword($oldPassword, $newPassword);

        if ($result) {
            $success = [
                "status" => "success",
                "message" => "Je wachtwoord is succesvol gewijzigd."
            ];
            echo json_encode($success);
        }
        else{
            $error = [
                "status" => "error",
                "message" => "Het oude wachtwoord is onjuist.",
                "error_div" => "current_password"
            ];
            echo json_encode($error);
        }
    } catch (Exception $e) {
        $error = [
            "status" => "error",
            "message" => $e->getMessage()
        ];
        echo json_encode($error);
    }
} else {
    $error = [
        "status" => "error",
        "message" => "Ongeldig verzoek. Alleen POST is toegestaan."
    ];
    echo json_encode($error);
}