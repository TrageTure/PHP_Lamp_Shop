<?php
    session_start();
    if ($_SESSION['loggedin'] !== true) {
        header('location: login.php');
    }
    include_once('../components/db.php');
    // $stmt = $conn->prepare('SELECT * FROM products');
    // $stmt->execute();
    // $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?><!DOCTYPE html>
<html lang="en">
<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/index.css">
    <title>Home</title>
</head>
<body>
    <?php include_once("../components/nav.php"); ?> 
    <!-- <?php foreach($products as $p):?>
    <article>
        <h2><?php echo $p['title']?>: <?php echo $p['price']?>€</h2>
    </article>
    <?php endforeach?> -->

    <section class="products">
        <article>
            <div class="product_img groen"></div>
            <div class="info_container">
                <h2 class="name">Lamp groen</h2>
                <p class="price">44.99$</p>
                <p class="description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In distinctio ab necessitatibus quos ullam perspiciatis dignissimos quas impedit qui laudantium, officia culpa, quod quidem nisi delectus ipsa repellendus autem optio?
                </p>
            </div>
        </article>

        <article>
            <div class="product_img geel"></div>
            <div class="info_container">
                <h2 class="name">Lamp groen</h2>
                <p class="price">44.99$</p>
                <p class="description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In distinctio ab necessitatibus quos ullam perspiciatis dignissimos quas impedit qui laudantium, officia culpa, quod quidem nisi delectus ipsa repellendus autem optio?
                </p>
            </div>
        </article>

        <article>
            <div class="product_img blauw"></div>
            <div class="info_container">
                <h2 class="name">Lamp groen</h2>
                <p class="price">44.99$</p>
                <p class="description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In distinctio ab necessitatibus quos ullam perspiciatis dignissimos quas impedit qui laudantium, officia culpa, quod quidem nisi delectus ipsa repellendus autem optio?
                </p>
            </div>
        </article>

        <article>
            <div class="product_img geel"></div>
            <div class="info_container">
                <h2 class="name">Lamp groen</h2>
                <p class="price">44.99$</p>
                <p class="description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In distinctio ab necessitatibus quos ullam perspiciatis dignissimos quas impedit qui laudantium, officia culpa, quod quidem nisi delectus ipsa repellendus autem optio?
                </p>
            </div>
        </article>

        <article>
            <div class="product_img blauw"></div>
            <div class="info_container">
                <h2 class="name">Lamp groen</h2>
                <p class="price">44.99$</p>
                <p class="description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In distinctio ab necessitatibus quos ullam perspiciatis dignissimos quas impedit qui laudantium, officia culpa, quod quidem nisi delectus ipsa repellendus autem optio?
                </p>
            </div>
        </article>

        <article>
            <div class="product_img blauw"></div>
            <div class="info_container">
                <h2 class="name">Lamp groen</h2>
                <p class="price">44.99$</p>
                <p class="description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In distinctio ab necessitatibus quos ullam perspiciatis dignissimos quas impedit qui laudantium, officia culpa, quod quidem nisi delectus ipsa repellendus autem optio?
                </p>
            </div>
        </article>

        <article>
            <div class="product_img groen"></div>
            <div class="info_container">
                <h2 class="name">Lamp groen</h2>
                <p class="price">44.99$</p>
                <p class="description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In distinctio ab necessitatibus quos ullam perspiciatis dignissimos quas impedit qui laudantium, officia culpa, quod quidem nisi delectus ipsa repellendus autem optio?
                </p>
            </div>
        </article>

        <article>
            <div class="product_img geel"></div>
            <div class="info_container">
                <h2 class="name">Lamp groen</h2>
                <p class="price">44.99$</p>
                <p class="description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. In distinctio ab necessitatibus quos ullam perspiciatis dignissimos quas impedit qui laudantium, officia culpa, quod quidem nisi delectus ipsa repellendus autem optio?
                </p>
            </div>
        </article>
    </section>
</body>
</html>