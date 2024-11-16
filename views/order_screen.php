<?php 
// session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/order.css">
    <title>Order</title>
</head>
<body>
    <?php include_once('../components/nav.php');?>
    <main class="main">

        <section class="orderlines">
            <h1 class="artikelen_titel">Artikelen in winkelwagen:</h1>
            <div class="order_item">
                <img class="order_shopping_cart_item_img" src='../images/product_images/67277a2ad8a95__DSC1549.jpg'>
                <div class="order_shopping_cart_info_grid">
                    <div class="order_shopping_cart_item_name_color_size">
                        <h1 class="order_shopping_cart_item_name">Koe in zon</h1>
                        <p>Blauw, small</p>
                    </div>
                    <div class="order_delete" data-index=""></div> 
                    <h2 class="order_shopping_cart_item_price">€20,99</h2>
                    <p class="order_shopping_cart_item_amount">Aantal: 2</p>
                </div>
            </div>
            <div class="order_divider2"></div>

            <div class="order_item">
                <img class="order_shopping_cart_item_img" src='../images/product_images/67277a2ad8a95__DSC1549.jpg'>
                <div class="order_shopping_cart_info_grid">
                    <div class="order_shopping_cart_item_name_color_size">
                        <h1 class="order_shopping_cart_item_name">Koe in zon</h1>
                        <p>Blauw, small</p>
                    </div>
                    <div class="order_delete" data-index=""></div> 
                    <h2 class="order_shopping_cart_item_price">€20,99</h2>
                    <p class="order_shopping_cart_item_amount">Aantal: 2</p>
                </div>
            </div>
            <div class="order_divider2"></div>

            <div class="order_item">
                <img class="order_shopping_cart_item_img" src='../images/product_images/67277a2ad8a95__DSC1549.jpg'>
                <div class="order_shopping_cart_info_grid">
                    <div class="order_shopping_cart_item_name_color_size">
                        <h1 class="order_shopping_cart_item_name">Koe in zon</h1>
                        <p>Blauw, small</p>
                    </div>
                    <div class="order_delete" data-index=""></div> 
                    <h2 class="order_shopping_cart_item_price">€20,99</h2>
                    <p class="order_shopping_cart_item_amount">Aantal: 2</p>
                </div>
            </div>
            <div class="order_divider2"></div>

        </section>

        <section class="left">
            <h1 class="title_left">Overzicht:</h1>
            <div class="delivery_options">
                <h2>Levering:</h2>
                <div class="delivery_radio"> 
                    <div class="standard">
                        <input id="standard" type="radio" name="delivery" value="standard" checked/>
                        <label for="standard"></label>
                        <div class="flex_delivery_grid">
                            <h3>Standard (gratis)</h3>
                            <p>Ten laatste geleverd binnen 7 werkdagen</p>
                        </div>
                    </div>
                    <div class="express">
                        <input id="express" type="radio" name="delivery" value="express" />
                        <label for="express"></label>
                        <div class="flex_delivery_grid">
                            <h3>Express (€7.99)</h3>
                            <p>Ten laatste geleverd binnen 3 werkdagen</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="total_price">
                <h2>Subtotaal:</h2>
                <h2>€62,97</h2>
            </div>
            <div class="total_price">
                <h2>Verzendkosten:</h2>
                <h2>€62,97</h2>
            </div>
            <div class="total_price">
                <h2>Totaal bedrag:</h2>
                <h2>€62,97</h2>
            </div>

            <h3 class="order_btn_bestellen">Bestellen!</h3>
        </section>
    </main>
</body>
</html>