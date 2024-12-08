<?php 
session_start();
// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

include_once('../classes/Pictures.php');
include_once('../classes/DeliveryLocations.php');
include_once('../classes/User.php');

$user = new User();
$result = $user->getAllFromEmail($_SESSION['email']);
$user_id = $result['id'];
$user_pf_pic = $result['profile_pic'];

// Verwerk het formulier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delivery_location']) && !empty($_POST['delivery_location'])) {
        
        $_SESSION['delivery_location'] = $_POST['delivery_location'];

        // Redirect naar stap 3
        header('Location: ../views/order_pay.php');
        exit;
    } else {
        $error = "Selecteer een afleveradres.";
    }
}

$activeLocation = DeliveryLocation::getActive($user_id);
$deliveryLocations = DeliveryLocation::getNotActive($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/acc.css">
    <title>Adres kiezen</title>
</head>
<body>
    <?php include_once('../components/nav.php'); ?>
    <main id="main">
        <form action="" method="POST" class="flex_form">
            <h1>Waar wil je je pakketje laten leveren?</h1>

            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <?php if (!empty($activeLocation) || !empty($deliveryLocations)):?>
                <?php if (!empty($activeLocation)): ?>
                <div>
                    <h2>Actief Adres</h2>
                    <div class="adress_grid_order">
                        <input type="radio" name="delivery_location" value="<?php echo $activeLocation['id']; ?>" checked>
                        <p class="adress_name"><?php echo $activeLocation['adress_naam']; ?></p>
                        <p><?php echo "{$activeLocation['street_name']} {$activeLocation['house_number']}"; ?></p>
                        <p><?php echo "{$activeLocation['postal_code']} {$activeLocation['city']}"; ?></p>
                        <p><?php echo $activeLocation['country']; ?></p>
                    </div>
                </div>
                <?php endif?>
                
                <?php if (!empty($deliveryLocations)): ?>
                    <div>
                        <h2>Alle adressen</h2>
                        <div class="delivery_location">
                            <?php foreach ($deliveryLocations as $location): ?>
                                <div class="location">
                                    <label>
                                        <div class="adress_grid_order">
                                            <input type="radio" name="delivery_location" value="<?php echo $location['id']; ?>">
                                            <p class="adress_name"><?php echo $location['adress_naam']; ?></p>
                                            <p><?php echo "{$location['street_name']} {$location['house_number']}"; ?></p>
                                            <p><?php echo "{$location['postal_code']} {$location['city']}"; ?></p>
                                            <p><?php echo $location['country']; ?></p>
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif?>

            <button type="submit" id="btn_adress">Volgende stap</button>
            <?php else:?>
                <p>Je hebt nog geen adressen toegevoegd. <span><a href="../views/account.php">Ga naar je account om een adres toe te voegen.</a></span></p>
            <?php endif;?>

        </form>
    </main>
</body>
</html>