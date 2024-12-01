<?php
include_once('../classes/Db.php');
include_once('../classes/Product.php');
include_once('../classes/ProductOptions.php');

$totalAmount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $totalAmount += $item['amount'];
    }
}
?>

<link rel="stylesheet" href="../css/components.css">
<nav>
    <a href="index.php" class="logo"></a>
    <div class="container_nav">
        <div class="shopping_cart_flex">
            <div class="shopping_cart">
                <p class="count"><?php echo $totalAmount?></p>
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
                $thumbnail = $thumb->getThumbnailByProductId($item['product_id']);

                $options = new Options();
                $color = $options->getColorById($item['color_id']);
                $size = $options->getSizeById($item['size_id']);
            ?>
            <div class="item">
                <img class="shopping_cart_item_img" src='../images/product_images/<?php echo htmlspecialchars($thumbnail); ?>'>
                <div class="shopping_cart_info_grid">
                    <div class="shopping_cart_item_name_color_size">
                        <h1 class="shopping_cart_item_name"><?php echo htmlspecialchars($item['name']); ?></h1>
                        <p><?php echo $color['color_name'].", ".$size['size']?></p>
                    </div>
                    <div class="delete" data-index="<?php echo $index; ?>"></div> 
                    <h2 class="shopping_cart_item_price"><?php echo htmlspecialchars($item['price']); ?>€</h2>
                    <p class="shopping_cart_item_amount">Aantal: <?php echo htmlspecialchars($item['amount']); ?></p>
                </div>
            </div>
            <div class="divider2"></div>
        <?php endforeach; ?>

        <?php if (empty($_SESSION['cart'])): ?>
            <h2 class="empty_cart">Je winkelwagen is leeg</h2>
            <h3 class="btn_bestellen hidden">Bestellen!</h3>
        <?php else: ?>
            <div class="totaal_bedrag">
                <h2>Totaal bedrag:</h2>
                <h2><?php 
                $totalPricePerItem = 0;
                $totalPricePerItem = 0;
                foreach($_SESSION['cart'] as $index => $item){
                    $totalPricePerItem += $item['price'] * $item['amount'];
                }
                echo '€'.number_format($totalPricePerItem, 2, '.', '');
                ?></h2>
            </div>
            <h3 class="btn_bestellen">Bestellen!</h3>
        <?php endif; ?>
        </div>
    </div>
    <script src="../js/components.js"></script>
</nav>