<?php 
session_start();
if ($_SESSION['loggedin'] !== true) {
    header('location: login.php');
}
include_once('../classes/Product.php');
include_once('../classes/Pictures.php');
include_once('../classes/ProductOptions.php');



$product = new Product();
$product->getProductById($_GET['id']);

$options = new Options();
$uniqueColors = $options->getUniqueColorsByProductId($_GET['id']); 
$uniqueSizes = $options->getUniqueSizesByProductId($_GET['id']);

//stock_color_size = $options->getStockAmountByColorAndSize($_GET['id'], $colorId, $sizeId);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/details.css"> 
    <title>Details</title>
</head>
<body>
    <?php include_once('../components/nav.php'); ?>
    <main class="main">
        <div class="left">
            <?php 
                $images = new Images();
                $productImages = $images->getImagesByProductId($_GET['id']); 
                foreach ($productImages as $image): 
            ?>
                <img src='../images/product_images/<?php echo htmlspecialchars($image['url']); ?>' class="image">
            <?php endforeach; ?>
        </div>
        <div class="right">
            <section>
                <div class="container_title_price">
                    <h1><?php echo htmlspecialchars($product->getTitle()); ?></h1>
                    <h2><span id="price_display"></span></h2>
                </div>
                <p><?php echo htmlspecialchars($product->getDescription()); ?></p>
            </section>
            <section class="option_color">
                <h2>Color:</h2>
                <div class="color_radio"> 
                    <?php foreach ($uniqueColors as $color): ?>
                        <input type="radio" name="color" id="<?php echo $color['color_name']; ?>" value="<?php echo $color['id'] ?>"/>
                        <label for="<?php echo $color['color_name']; ?>"><?php echo ucfirst($color['color_name']); ?></label>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="option_size">
                <h2>Size:</h2>
                <div class="size_radio"> 
                    <?php foreach($uniqueSizes as $size):?>
                    <input type="radio" name="size" id="<?php echo $size['size']?>" value="<?php echo $size['id'] ?>" />
                    <label for="<?php echo $size['size']?>"><?php echo ucfirst($size['size'])?></label>
                    <?php endforeach?>
                </div>
            </section>

            <section class="deliver_amount">
                <div class="delivery_options">
                    <h2>Delivery:</h2>
                    <div class="delivery_radio"> 
                        <input type="radio" name="delivery" id="standard" />
                        <label for="standard">Standard (free)</label>
    
                        <input type="radio" name="delivery" id="express" />
                        <label for="express">Express (+€10,99)</label>
                    </div>
                </div>
                <div class="container_stock_price">
                    <h2 class="stock">In stock: <span class="span_color" id="stock_display"></span></h2>
    
                    <div class="container_price_cart">
                        <div class="add">
                            <p class="minus"></p>
                            <p class="amount_count">0</p>
                            <p class="plus"></p>
                        </div>
                        <div class="add_to"></div>
                    </div>
                </div>
            </section>

        </div>
    </main>
    <section class="recomended">
        <h2>Recomended:</h2>
        <article class="recomended_article">
            <img src="" alt="" class="product_img">
            <div class="info_container">
                <h2 class="name"></h2>
                <p class="price"></p>
                <p class="description"></p>

            </div>
        </article>


    </section>
    <script>
        const productId = <?php echo json_encode($_GET['id']); ?>;
    </script>
    <script src="../js/product_details.js"></script>
</body>
</html>