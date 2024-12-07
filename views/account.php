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
include_once('../classes/Orderline.php');

$user = new User();
$result = $user->getAllFromEmail($_SESSION['email']);
$userid = $result['id'];

$locations = Deliverylocation::getDeliveryLocations($userid);
$activeLocation = Deliverylocation::getActive($userid);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/acc.css">
    <title>Document</title>
</head>
<body>
    <?php include_once('../components/nav.php')?>
    <main>
        <section class="acc_left">
            <section class="account_info">
                <div class="flex_img">
                    <div class="flex_img_name">
                        <img src="../images/0bd73bfec9d3f645b06bea1a433fc642.gif" alt="profile picture">
                        <div>
                            <h1><?php echo $result['first_name'] ?></h1>
                            <p><?php echo $result['email'] ?></p>
                        </div>
                    </div>
                    <div class="profile_btns">
                        <p class="btn_adress" id="edit_ww">Edit</p>
                        <a href="../process/logout.php" class="btn_adress">Log out</a>
                    </div>
                </div>
        
                <div class="balance">
                    <h2>Balans:</h2>
                    <p>€<?php echo $result['currency']?></p>
                </div>

                <p class="add_balance">Balans toevoegen</p>
            </section>
            <div id="bckgrnd_password" class="hidden">
                <section class="password-modal" id="password-modal">
                    <div class="modal-content">
                        <h2>Wachtwoord aanpassen</h2>
                        <form id="passwordForm">
                            <label for="current_password">Huidig wachtwoord:</label>
                            <input type="password" id="current_password" name="current_password" required>
    
                            <label for="new_password">Nieuw wachtwoord:</label>
                            <input type="password" id="new_password" name="new_password" required>
    
                            <label for="confirm_password">Bevestig nieuw wachtwoord:</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>

                            <div class="error hidden" id="error"></div>
    
                            <button type="submit" class="btn_save">Opslaan</button>
                            <button type="button" class="btn_cancel" id="cancel-password">Annuleren</button>
                        </form>
                    </div>
                </section>
            </div>

            <section class="adresses">
            <div class="adress">
                    <h2>Levering adress</h2>
                    <div class="btn_grid">
                        <p class="btn_adress" id="choose_btn">Choose one</p>
                    </div>
                </div>
                <div class="adress_grid_chosen">
                    <?php if (!$activeLocation):?>
                        <p>Geen actief adres</p>
                    <?php else:?>
                    <p class="adress_name"><?php echo $activeLocation['adress_naam']?></p>
                    <p><?php echo $activeLocation['street_name']." ".$activeLocation['house_number']?></p>
                    <p><?php echo $activeLocation['postal_code']." ".$activeLocation['city']?></p>
                    <p><?php echo $activeLocation['country']?></p>
                    <?php endif?>
                </div>

                <div class="adress">
                    <h2>Overige adressen</h2>
                    <div class="btn_grid">
                        <p class="btn_adress" id="add_adress">Add</p>
                        <p class="btn_adress" id="editButton">Edit</p>
                    </div>
                </div>
                <div class="adress_flex_">
                    <?php if (empty($locations)):?>
                        <div class="adress_grid">
                            <p>Geen adressen</p>
                        </div>
                    <?php else:?>
                    <?php foreach($locations as $location):?>
                        <div class="adress_grid">
                            <div class="delete_adress" id="delete_adress" data-id="<?php echo $location['id'] ?>"></div>
                            <p class="adress_name"><?php echo $location['adress_naam']?></p>
                            <p><span class="street_name"><?php echo $location['street_name']?></span> <span class="house_number"><?php echo $location['house_number']?></span></p>
                            <p><span class="postal_code"><?php echo $location['postal_code']?></span> <span class="city"><?php echo $location['city']?></span></p>
                            <p><?php echo $location['country']?></p>
                        </div>
                        <div class="divider_adress" data-id="<?php echo $location['id'] ?>"></div>
                    <?php endforeach?>
                    <?php endif?>
                </div>
            </section>
        </section>

        <section class="right">
            <h1>Vorige bestellingen</h1>
            <?php 
            $orders = Order::getAllOrdersById($userid);
            foreach($orders as $order  ):
                $delivery = Deliverylocation::getAdressById($order['delivery_location_id']);
            ?>
            <div class="bestelling">
                <h2>Bestelling van <?php echo $order['order_date']?></h2>
                <div class="divider_adress"></div>
                <div class="images_flex">
                    <?php
                    $orderline = Orderline::getOrderlinesByOrderId($order['id']);
                    foreach ($orderline as $line):
                        $product = new Product();
                        $product = $product->getProductById($line['product_id']);

                        if (!$product) {
                            echo "Product niet gevonden.";
                            continue;
                        }

                        $thumb = new Images();
                        $thumbnail = $thumb->getThumbnailByProductId($product->getId());
                    ?>
                        <img src="../images/product_images/<?php echo htmlspecialchars($thumbnail); ?>" alt="">
                    <?php endforeach; ?>
                </div>
                <div class="flex_bestelling">
                    <p>Status:</p>
                    <p style="color: green;">GELEVERD</p>
                </div>
                <div class="flex_bestelling">
                    <p>Bestelnummer:</p>
                    <p><?php echo $order['id']?></p>
                </div>
                <div class="flex_bestelling">
                    <p>Bedrag:</p>
                    <p>€<?php echo $order['full_price']?></p>
                </div>
                <div class="flex_bestelling">
                    <p>Verzendadres:</p>
                    <p>
                    <?php echo $delivery['street_name']." ".$delivery['house_number'].", ".$delivery['postal_code']." ".$delivery['city'].", ".$delivery['country']?></p> 
                </div>
            </div>
            <?php endforeach?>
        </section>

        <div class="background hidden" id="background">
            <section class="popup">
                <div class="modal-content">
                    <div class="close"></div>
                    <h2>Nieuw Adres Toevoegen</h2>
                    <form id="addressForm" method="POST">
                        <label for="name">Naam:</label>
                        <input type="text" id="name" name="adress_naam" required>
                        <label for="street">Straat:</label>
                        <input type="text" id="street" name="street_name" required>
                        <label for="number">Huisnummer:</label>
                        <input type="text" id="number" name="house_number" required>
                        <label for="postal_code">Postcode:</label>
                        <input type="text" id="postal_code" name="postal_code" required>
                        <label for="city">Stad:</label>
                        <input type="text" id="city" name="city" required>
                        <label for="country">Land:</label>
                        <input type="text" id="country" name="country" required>
                        <button type="submit" class="submit">Opslaan</button>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <script src="../js/acc.js"></script>
</body>
</html>