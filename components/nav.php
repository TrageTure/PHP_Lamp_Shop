<?php 
include_once('../classes/Db.php');
include_once('../classes/Product.php');
include_once('../classes/ProductOptions.php');


?><link rel="stylesheet" href="../css/components.css">
<nav>
    <a href="index.php" class="logo"></a>
    <div class="container_nav">
        <div class="shopping_cart_flex">
            <div class="shopping_cart">
                <p class="count">
                    1
                </p>
            </div>
            <div class="indicator"></div>
        </div>
        <div class="account"></div>
    </div>

    <div class="shopping_cart_items">
        <div class="items">
        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
            <?php
                $thumb = new Images();
                $thumbnail = $thumb->getThumbnailByProductId($_SESSION['cart'][$index]['product_id']);
            ?>
            <div class="item">
                <img class="shopping_cart_item_img" src='../images/product_images/<?php echo htmlspecialchars($thumbnail); ?>'></img>
                <div class="shopping_cart_info_grid">
                    <h2 class="shopping_cart_item_name"><?php echo $item['name']; ?></h2>
                    <div class="delete" data-index="<?php echo $index; ?>"></div> <!-- Voeg data-index toe -->
                    <h2 class="shopping_cart_item_price"><?php echo $item['price']; ?>€</h2>
                    <p class="shopping_cart_item_amount"> Aantal: <?php echo $item['amount']; ?></p>
                </div>
            </div>
            <div class="divider2"></div>
        <?php endforeach; ?>

            <div class="totaal_bedrag">
                <h2>Totaal bedrag:</h2>
                <h2>44.99€</h2>
            </div>
            <h3 class="btn_bestellen">Bestellen!</h3>
        </div>
        
    </div>
    <script src="../js/components.js"></script>
</nav>