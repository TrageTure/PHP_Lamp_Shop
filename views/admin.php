<?php
include_once('../classes/Product.php');
include_once('../classes/ProductOptions.php');
include_once('../classes/Pictures.php');

// Fetch categories, colors, and sizes
$category = new Product();
$categories = $category->getAllCategories();

$options = new Options();
$allcolors = $options->getAllColors();
$allSizes = $options->getAllSizes();

if (!empty($_POST['product_id'])) {
    try {
        $product = new Product();
        $product->deleteProduct($_POST['product_id']);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

if (!empty($_POST['title']) && !empty($_POST['category_id']) && isset($_FILES['images'])) {
    try {
        // Create product
        $product = new Product();
        $product->setTitle($_POST['title']);
        $product->setCategoryId($_POST['category_id']);
        $product->setDescription($_POST['description']);
        
        // Save the product and get its ID
        $product->save();
        $productId = $product->getId();

        if (!$productId) {
            throw new Exception("Product ID is not generated. Check the product save process.");
        }
        
        // Handle Images
        $uploadedImages = $_FILES['images'];
        if (count($uploadedImages['name']) > 4) {
            throw new Exception("Je kunt maximaal 4 afbeeldingen uploaden.");
        }

        $imageInstance = new Images();
        for ($i = 0; $i < count($uploadedImages['name']); $i++) {
            $imageName = uniqid() . "_" . basename($uploadedImages['name'][$i]);
            $imagePath = "../images/product_images/" . $imageName;
            if (move_uploaded_file($uploadedImages['tmp_name'][$i], $imagePath)) {
                $imageInstance->addImage($productId, $imageName);
            } else {
                throw new Exception("Afbeelding kon niet worden geüpload.");
            }
        }

        // Handle Options
        if (!empty($_POST['colors']) && !empty($_POST['sizes']) && isset($_POST['stock_amount']) && isset($_POST['price'])) {
            foreach ($_POST['colors'] as $colorId) {
                foreach ($_POST['sizes'] as $sizeId) {
                    $productOption = new Options();
                    $productOption->setProductId($productId);
                    $productOption->setColorId($colorId);
                    $productOption->setSizeId($sizeId);
                    $productOption->setStockAmount($_POST['stock_amount'][$colorId][$sizeId]);
                    $productOption->setPrice($_POST['price'][$colorId][$sizeId]);
                    $productOption->save();
                }
            }
        }

        echo "Product succesvol toegevoegd!";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch all products for deletion dropdown
$productInstance = new Product();
$products = $productInstance->getAllProducts();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="#" method="post" enctype="multipart/form-data" class="form">
        <h1>Nieuw product toevoegen</h1>
        <div class="upload_form">
            <label for="title">Title:</label>
            <input type="text" name="title" class="input_field">
        </div>
        <!-- Maximaal 4 images -->
        <div class="upload_form">
            <label for="images">Images (max 4):</label>
            <input type="file" class="input_field" name="images[]" multiple>
        </div>
        <div class="upload_form">
            <label for="description">Description:</label>
            <input type="text" name="description" class="input_field">
        </div>
        <div class="upload_form">
            <label for="category_id">Category:</label>
            <?php foreach($categories as $c): ?>
                <input type="radio" name="category_id" value="<?php echo $c['id']?>" class="input_field">
                <label for="category_id"><?php echo $c['title']?></label>
            <?php endforeach; ?>
        </div>
        <div>
            <h2>Select Options</h2>
            
            <p>Colors:</p>
            <?php foreach($allcolors as $ac): ?>
                <input type="checkbox" name="colors[]" value="<?php echo $ac['id']; ?>" class="input_field">
                <label><?php echo $ac['color_name']; ?></label>
            <?php endforeach; ?>
            
            <br>
            
            <p>Sizes:</p>
            <?php foreach($allSizes as $as): ?>
                <input type="checkbox" name="sizes[]" value="<?php echo $as['id']; ?>" class="input_field">
                <label><?php echo $as['size']; ?></label>
            <?php endforeach; ?>
            
            <br>
            
            <p>Stock Amount and Price for Each Combination:</p>
            <?php foreach($allcolors as $ac): ?>
                <?php foreach($allSizes as $as): ?>
                    <div class="upload_form">
                        <label><?php echo $ac['color_name'] . " - " . $as['size']; ?>:</label>
                        <input type="number" name="stock_amount[<?php echo $ac['id']; ?>][<?php echo $as['id']; ?>]" class="input_field" placeholder="Stock">
                        <input type="number" step="0.01" name="price[<?php echo $ac['id']; ?>][<?php echo $as['id']; ?>]" class="input_field" placeholder="Price">
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
        <div class="upload_form btn">
            <input type="submit" value="upload" class="btn">
        </div>
        <?php if(isset($error)): ?>
            <div class="error">
                <p><?php echo $error ?></p>
            </div>
        <?php endif; ?>
    </form>

    

    <form action="#" method="post">
        <!-- Producten verwijderen -->
        <h1>Producten verwijderen</h1>
        <div class="upload_form">
            <label for="product_id">Product:</label>
            <select name="product_id" class="input_field">
                <?php foreach($products as $p): ?>
                    <option value="<?php echo $p['id'] ?>"><?php echo $p['title'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="upload_form btn">
            <input type="submit" value="delete" class="btn">
        </div>
    </form>
</body>
<script>
function checkFileLimit(input) {
    if (input.files.length > 4) {
        alert("Je kunt maximaal 4 afbeeldingen uploaden.");
        input.value = ""; 
    }
}
</script>
</html>