<?php 
session_start();

include_once('../classes/User.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Je moet ingelogd zijn om een review te schrijven.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    $result = $user->getAllFromEmail($_SESSION['email']);
    $user_id = $result['id'];

    $target_dir = "../images/pf_pics/";
    $uploadedImages = $_FILES['profile_picture'];

    $uploadedImagesName = uniqid() . "_" . basename($uploadedImages['name']);
    $target_file = $target_dir . $uploadedImagesName;

    $currentProfilePic = $result['profile_pic']; 

    if ($currentProfilePic && $currentProfilePic !== 'standard_pf_pic.svg') { 
        $currentFilePath = $target_dir . $currentProfilePic;
        if (file_exists($currentFilePath)) {
            unlink($currentFilePath); 
        }
    }

    // 3. Verplaats de nieuwe afbeelding en werk de database bij
    if (move_uploaded_file($uploadedImages['tmp_name'], $target_file)) {
        $user->updateProfilePic($user_id, $uploadedImagesName);
        echo json_encode(['success' => true, 'message' => $uploadedImagesName]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Afbeelding kon niet worden geüpload.']);
        exit;
    }
}
?>