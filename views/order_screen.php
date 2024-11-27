<?php 
session_start();
if ($_SESSION['loggedin'] !== true) {
    header('location: login.php');
}
include_once('../classes/Product.php');
include_once('../classes/Pictures.php');
include_once('../classes/ProductOptions.php');
include_once('../classes/Order.php');

$product = new Product();
$randomProducts = $product->getRandomProducts();

$options = new Options(); 


$amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $amount += $item['amount'];
}

$totalPrice = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalPrice += $item['price'] * $item['amount'];
}

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
            <?php if(!empty($_SESSION['cart'])):?>
            <h1 class="artikelen_titel">Artikelen in winkelwagen:</h1>
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
                        <div class="order_delete" data-index="<?php echo $index; ?>"></div> 
                        <h2 class="order_shopping_cart_item_price"><?php echo htmlspecialchars($item['price']); ?>€</h2>
                        <div class="order_shopping_cart_item_amount">
                        <div id="add">
                            <p class="minus"></p>
                            <p class="amount_count"><?php echo $item['amount']?></p>
                            <p class="plus"></p>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="order_divider2"></div>
            <?php endforeach?>
            <?php else:?>
                <h1 class="artikelen_titel">Geen artikelen in winkelwagen</h1>
            <?php endif?>

        </section>

        <section class="right">
            <section class="right_top">
                <div class="delivery_options">
                    <h1>Levering:</h1>
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
    
                <h3 class="order_btn_bestellen">Bestellen!</h3>
            </section>
    
            <section class="right_bottom">
                <h1 class="title_left">Overzicht:</h1>
                <div class="aantal_artikelen">
                    <p><?php echo $amount?> artikel(en)</p>
                    <p class="product_price">€<?php echo $totalPrice?></p>
                </div>
                <div class="verzendkosten">
                    <p class="delivery_type">Standard</p>
                    <p class="delivery_price">€0</p>
                </div>
                <div class="divider"></div>
                <div class="totaal">
                    <h2>Totaal:</h2>
                    <h2 class="total_price">€<?php echo $totalPrice?></h2>
                </div>
            </section>
        </section>
    </main>
    <section class="recomended">
        <h2>Meer producten zoals deze:</h2>
        <section class="products">
            <?php foreach($randomProducts as $p):?>
                <?php
                $thumb = new Images();
                $thumbnail = $thumb->getThumbnailByProductId($p['id']);
                ?>
                <article onclick="window.location.href='product_details.php?id=<?php echo $p['id']; ?>';">
                    <img src='../images/product_images/<?php echo htmlspecialchars($thumbnail); ?>' class="product_img">
                    <div class="info_container">
                        <h1 class="name"><?php echo htmlspecialchars($p['title']); ?></h1>
                        <h2 class="price">€<?php echo htmlspecialchars($p['min_price'])?></h2>
                        <p class="description">
                            <?php echo htmlspecialchars($p['description']); ?>
                        </p>
                    </div>
                </article>
            <?php endforeach?>
        </section>
    </section>
    <script src="../js/order.js"></script>
</body>
</html>