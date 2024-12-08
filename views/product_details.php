<?php 
session_start();
if ($_SESSION['loggedin'] !== true) {
    header('location: login.php');
}
include_once('../classes/Product.php');
include_once('../classes/Pictures.php');
include_once('../classes/ProductOptions.php');
include_once('../classes/Reviews.php');
include_once('../classes/User.php');

$user = new User();
$result = $user->getAllFromEmail($_SESSION['email']);
$userid = $result['id'];
$user_pf_pic = $result['profile_pic'];

$product = new Product();
$product->getProductById($_GET['id']);
$allProductsByCategory = $product->getProductsByCategory($product->getCategoryId());

$options = new Options();
$uniqueColors = $options->getUniqueColorsByProductId($_GET['id']); 
$uniqueSizes = $options->getUniqueSizesByProductId($_GET['id']);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/all.css">
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
                        <div class="standard">
                            <h3>Standard (gratis)</h3>
                            <p>Ten laatste geleverd binnen 7 werkdagen</p>
                        </div>
                        <div class="express">
                            <h3>Express (€7.99)</h3>
                            <p>Ten laatste geleverd binnen 3 werkdagen</p>
                        </div>
                    </div>
                </div>
                <div class="container_stock_price">
                    <h2 class="stock">In stock: <span class="span_color" id="stock_display"></span></h2>
    
                    <div class="container_price_cart">
                        <div id="add">
                            <p class="minus"></p>
                            <p class="amount_count">0</p>
                            <p class="plus"></p>
                        </div>
                        <div class="add_to" id="add_to_cart_button"></div>
                    </div>
                    
                </div>
                <p class="error">Error</p>
            </section>

        </div>
    </main>
    <section class="reviews">
        <div class="reviews_flex_title">
            <h2>Reviews</h2>
            <p id="review_btn">Schrijf review</p>
        </div>
        <div class="reviews_grid" id="reviews_grid">
            <?php 
            $initialReviews = Review::getReviewsByProductId($_GET['id'], 6, 0); // Laad de eerste 6 reviews
            foreach ($initialReviews as $review): 
            ?>
                <section class="review_container">
                    <div class="review">
                        <div class="flex_review">
                            <img src="../images/pf_pics<?php $review['profile_pic']  ?>" alt="profile_pic_review">
                            <div id="right">
                                <div class="flex_name_stars">
                                    <h3><?php echo htmlspecialchars($review['first_name'] . " " . $review['last_name']); ?></h3>
                                    <div class="stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <div class="star <?php echo $i <= $review['stars_amount'] ? 'filled' : 'empty'; ?>"></div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p><?php echo htmlspecialchars($review['review']); ?></p>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
        <p id="loadMoreReviews" data-offset="6" data-limit="6" data-product_id="<?php echo htmlspecialchars($_GET['id']); ?>">Laad meer reviews</p>

        <div id="background">
            <section class="review_form_section">
                <div class="review_form_flex">
                    <h2>Schrijf een review</h2>
                    <div id="close"></div>
                </div>
                <form id="reviewForm" method="POST" action="../process/add_review.php">
                    <div class="form_group">
                        <h3 for="rating">Beoordeling:</h3>
                        <div class="rating">
                            <input type="radio" id="star5" name="rating" value="5" required><label for="star5" title="5 sterren"></label>
                            <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 sterren" class="ster"></label>
                            <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 sterren" class="ster"></label>
                            <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 sterren" class="ster"></label>
                            <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1 ster" class="ster"></label>
                        </div>
                    </div>
                    <div class="form_group">
                        <h3 for="review">Review:</h3>
                        <textarea id="review" name="review" rows="4" required></textarea>
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
                    </div>
                    <button type="submit" id="submit">Verzenden</button>
                    <p></p class="error_rev">
                </form>
            </section>
        </div>

    </section>
    <section class="recomended">
        <h2>Meer producten zoals deze:</h2>
        <section class="products">
            <?php foreach($allProductsByCategory as $p):?>
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
    <script>
        const productId = <?php echo json_encode($_GET['id']); ?>;
    </script>
    <script src="../js/product_details.js"></script>
    <script src="../js/components.js"></script>
</body>
</html>
