<?php
session_start();
if ($_SESSION['loggedin'] !== true) {
    header('location: /views/login.php');
}
include_once('../classes/Db.php');
include_once('../classes/Product.php');
include_once('../classes/Pictures.php');
include_once('../classes/User.php');

$user = new User();
$result = $user->getAllFromEmail($_SESSION['email']);
$userid = $result['id'];
$user_pf_pic = $result['profile_pic'];

$product = new Product();

if (!empty($_GET['category'])) {
    $products = $product->getProductsByCategory($_GET['category']);
} else {
    $products = $product->getAllProducts();
}

$categories = $product->getAllCategories(); 
?><!DOCTYPE html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/index.css">
    <title>Home</title>
</head>
<body>
    <?php include_once("../components/nav.php"); ?> 

    <section class="filter">
        <a href="index.php">All</a>
        <?php foreach ($categories as $category): ?>
            <a href="index.php?category=<?php echo $category['id']; ?>">
                <?php echo htmlspecialchars($category['title']); ?>
            </a>
        <?php endforeach; ?>
    </section>

    <section class="products">
        <?php foreach($products as $p): ?>
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
        <?php endforeach; ?>
    </section>
</body>
</html>