<?php 
session_start();
if ($_SESSION['loggedin'] !== true) {
    header('location: login.php');
}
include_once('../classes/User.php');
include_once('../classes/Order.php');
include_once('../classes/Pictures.php');

$user = new User();
$result = $user->getAllFromEmail($_SESSION['email']);
$userid = $result['id'];
$balance = $result['currency']; // Veronderstel dat de balans in de gebruikersgegevens zit

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/order_pay.css">
    <title>Betaling</title>
</head>
<body>
    <?php include_once('../components/nav.php')?>
    <main>

        <section class="orderlines">
            <?php if(!empty($_SESSION['cart'])):?>
            <h1 class="artikelen_titel">Overzicht</h1>
            <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                <?php
                    $thumb = new Images();
                    $thumbnail = $thumb->getThumbnailByProductId($item['product_id']);

                    $options = new Options();
                    $color = $options->getColorById($item['color_id']);
                    $size = $options->getSizeById($item['size_id']);
                    $uniqueStock = $options->getStockAmountByColorAndSize($item['product_id'], $item['color_id'], $item['size_id']);
                ?>
                <div class="order_item">
                    <img class="order_shopping_cart_item_img" src='../images/product_images/<?php echo htmlspecialchars($thumbnail); ?>'>
                    <div class="order_shopping_cart_info_grid">
                        <div class="order_shopping_cart_item_name_color_size">
                            <h1 class="order_shopping_cart_item_name"><?php echo htmlspecialchars($item['name']); ?></h1>
                            <p><?php echo $color['color_name'].", ".$size['size']?></p>
                        </div>
                        <h2 class="order_shopping_cart_item_price"><?php echo htmlspecialchars($item['price']); ?>€</h2>
                        <h3 class="amount_count"><?php echo $item['amount']?></h3>
                    </div>
                </div>
                <div class="order_divider2"></div>
            <?php endforeach?>
            <?php else:?>
                <h1 class="artikelen_titel">Geen artikelen in winkelwagen</h1>
            <?php endif?>
            
            <section class="payment_section">
            <h1>Betaling</h1>
            <p>Uw huidige saldo: €<?php echo number_format($balance, 2); ?></p>
            <form id="paymentForm" method="POST" action="../process/place_order.php">
                <div class="form_group">
                    <label for="amount">Te betalen bedrag:</label>
                    <input type="text" id="amount" name="amount" value="€<?php echo $_SESSION['total_price'] ?>" readonly> <!-- Verander dit naar het juiste bedrag -->
                </div>
                <button type="submit" class="submit">Betalen</button>
            </form>
        </section>
        <?php var_dump($_SESSION)?>
        </section>
    </main>
    <script src="../js/pay.js"></script>
</body>
</html>