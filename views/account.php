<?php 
session_start();
if ($_SESSION['loggedin'] !== true) {
    header('location: login.php');
}
include_once('../classes/Product.php');
include_once('../classes/Pictures.php');
include_once('../classes/ProductOptions.php');
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
                    <img src="../images/0bd73bfec9d3f645b06bea1a433fc642.gif" alt="profile picture">
                    <div>
                        <h1>Arthur De Klerck</h1>
                        <p>arthur.deklerck@gmail.com</p>
                    </div>
                </div>
        
                <div class="balance">
                    <h2>Balans:</h2>
                    <p>€188</p>
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
                    <p class="adress_name">Arthur De Klerck</p>
                    <p>Merbeekstraat 1</p>
                    <p>3360 Bierbeek</p>
                    <p>België</p>
                </div>

                <div class="adress">
                    <h2>Overige adressen</h2>
                    <div class="btn_grid">
                        <p class="btn_adress" id="add_adress">Add</p>
                        <p class="btn_adress" id="editButton">Edit</p>
                    </div>
                </div>
                <div class="adress_grid" data-id="1">
                    <div class="delete_adress"></div>
                    <p class="adress_name">Arthur De Klerck</p>
                    <p>Merbeekstraat 1</p>
                    <p>3360 Bierbeek</p>
                    <p>België</p>
                </div>
                <div class="divider_adress"></div>

                <div class="adress_grid" data-id="2">
                    <div class="delete_adress"></div>
                    <p class="adress_name">Arthur De Klerck</p>
                    <p>Merbeekstraat 1</p>
                    <p>3360 Bierbeek</p>
                    <p>België</p>
                </div>
                <div class="divider_adress"></div>

                <div class="adress_grid" data-id="3">
                    <div class="delete_adress"></div>
                    <p class="adress_name">Arthur De Klerck</p>
                    <p>Merbeekstraat 1</p>
                    <p>3360 Bierbeek</p>
                    <p>België</p>
                </div>
                <div class="divider_adress"></div>

                <div class="adress_grid" data-id="4">
                    <div class="delete_adress"></div>
                    <p class="adress_name">Arthur De Klerck</p>
                    <p>Merbeekstraat 1</p>
                    <p>3360 Bierbeek</p>
                    <p>België</p>
                </div>
                <div class="divider_adress"></div>
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
                    <form id="addressForm">
                        <label for="name">Naam:</label>
                        <input type="text" id="name" name="name" required>
                        <label for="street">Straat:</label>
                        <input type="text" id="street" name="street" required>
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