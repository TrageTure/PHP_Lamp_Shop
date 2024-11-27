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
                    <p class="btn_adress" id="edit_ww">Edit</p>
                </div>
        
                <div class="balance">
                    <h2>Balans:</h2>
                    <p>€<?php echo $result['currency']?></p>
                </div>

                <p class="add_balance">Balans toevoegen</p>
                <a href="../process/logout.php">Log out</a>
            </section>

            <section class="adresses">
            <div class="adress">
                    <h2>Levering adress</h2>
                    <div class="btn_grid">
                        <p class="btn_adress">Choose one</p>
                    </div>
                </div>
                <div class="adress_grid_chosen">
                    <p class="adress_name"><?php echo $activeLocation['adress_naam']?></p>
                    <p><?php echo $activeLocation['street_name']." ".$activeLocation['house_number']?></p>
                    <p><?php echo $activeLocation['postal_code']." ".$activeLocation['city']?></p>
                    <p><?php echo $activeLocation['country']?></p>
                </div>

                <div class="adress">
                    <h2>Overige adressen</h2>
                    <div class="btn_grid">
                        <p class="btn_adress" id="add_adress">Add</p>
                        <p class="btn_adress" id="editButton">Edit</p>
                    </div>
                </div>
                <?php foreach($locations as $location):?>
                    <div class="adress_grid" data-id="<?php echo $location['id'] ?>">
                        <div class="delete_adress" id="delete_adress"></div>
                        <p class="adress_name"><?php echo $location['adress_naam']?></p>
                        <p><?php echo $location['street_name']." ".$location['house_number']?></p>
                        <p><?php echo $location['postal_code']." ".$location['city']?></p>
                        <p><?php echo $location['country']?></p>
                    </div>
                    <div class="divider_adress"></div>
                <?php endforeach?>
            </section>
        </section>

        <section class="right">
            <h1>Vorige bestellingen</h1>
            <div class="bestelling">
                <h2>Bestelling van 19/01/2024</h2>
                <div class="divider_adress"></div>
                <div class="images_flex">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                </div>
                <div class="flex_bestelling">
                    <p>Status:</p>
                    <p style="color: green;">GELEVERD</p>
                </div>
                <div class="flex_bestelling">
                    <p>Bestelnummer:</p>
                    <p>37940</p>
                </div>
                <div class="flex_bestelling">
                    <p>Bedrag:</p>
                    <p>€278</p>
                </div>
                <div class="flex_bestelling">
                    <p>Verzendadres:</p>
                    <p>Merbeekstraat 1, 3360 Bierbeek, België</p>
                </div>
            </div>

            <div class="bestelling">
                <h2>Bestelling van 19/01/2024</h2>
                <div class="divider_adress"></div>
                <div class="images_flex">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                </div>
                <div class="flex_bestelling">
                    <p>Status:</p>
                    <p style="color: green;">GELEVERD</p>
                </div>
                <div class="flex_bestelling">
                    <p>Bestelnummer:</p>
                    <p>37940</p>
                </div>
                <div class="flex_bestelling">
                    <p>Bedrag:</p>
                    <p>€278</p>
                </div>
                <div class="flex_bestelling">
                    <p>Verzendadres:</p>
                    <p>Merbeekstraat 1, 3360 Bierbeek, België</p>
                </div>
            </div>

            <div class="bestelling">
                <h2>Bestelling van 19/01/2024</h2>
                <div class="divider_adress"></div>
                <div class="images_flex">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                    <img src="../images/product_images/67277a2ad8a95__DSC1549.jpg" alt="">
                </div>
                <div class="flex_bestelling">
                    <p>Status:</p>
                    <p style="color: green;">GELEVERD</p>
                </div>
                <div class="flex_bestelling">
                    <p>Bestelnummer:</p>
                    <p>37940</p>
                </div>
                <div class="flex_bestelling">
                    <p>Bedrag:</p>
                    <p>€278</p>
                </div>
                <div class="flex_bestelling">
                    <p>Verzendadres:</p>
                    <p>Merbeekstraat 1, 3360 Bierbeek, België</p>
                </div>
            </div>
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