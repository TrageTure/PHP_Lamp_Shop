<?php
session_start();
include_once('../classes/Product.php');
include_once('../classes/ProductOptions.php');
include_once('../classes/Pictures.php');
include_once('../classes/User.php');

// Zorg ervoor dat de gebruiker ingelogd en admin is
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
$user = new User();
$result = $user->getAllFromEmail($_SESSION['email']);
if ($result['is_admin'] === 0) {
    header('Location: ../views/index.php');
    exit;
}

// Haal product en opties op
$product = new Product();
$options = new Options();
$pictures = new Images();

if (isset($_GET['id'])) {
    $product->getProductById($_GET['id']);
    $productOptions = $options->getOptionsByProductId($_GET['id']);
    $productImages = $pictures->getAllFromImagesByProductId($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Bewerk productinformatie
        $product->setTitle($_POST['title']);
        $product->setCategoryId($_POST['category_id']);
        $product->setDescription($_POST['description']);
        $product->update();

        // Bewerk combinaties
        foreach ($_POST['options'] as $optionId => $optionData) {
            $option = new Options();
            $option->setId($optionId)
                   ->setStockAmount($optionData['stock'])
                   ->setPrice($optionData['price'])
                   ->update();
        }

        // Bewerk afbeeldingen
        foreach ($_FILES['images']['name'] as $imageId => $imageName) {
            if (!empty($imageName)) {
                $fileName = uniqid() . "_" . basename($imageName);
                move_uploaded_file($_FILES['images']['tmp_name'][$imageId], "../images/product_images/$fileName");
                $pictures->updateImage($imageId, $_GET['id'], $fileName);
            }
        }

        echo "Product succesvol bijgewerkt!";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$categories = $product->getAllCategories();
$currentCategory = $product->getCategoryId();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product bewerken</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <?php include_once('../components/nav.php')?>
    <main>
        <section class="admin_section">
            <h1>Product bewerken</h1>
            <form method="post" enctype="multipart/form-data">
                <div class="form_group">
                    <label for="title">Titel</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($product->getTitle()) ?>" required>
                </div>
                <div class="form_group">
                    <label for="category_id">Categorie</label>
                    <select id="category_id" name="category_id" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $category['id'] == $currentCategory ? 'selected' : '' ?>><?= htmlspecialchars($category['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form_group">
                    <label for="description">Beschrijving</label>
                    <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($product->getDescription()) ?></textarea>
                </div>
                <div class="form_group">
                    <h3>Combinaties Bewerken</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Kleur</th>
                                <th>Maat</th>
                                <th>Voorraad</th>
                                <th>Prijs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productOptions as $option): ?>
                                <tr>
                                    <td><?= htmlspecialchars($option['color_name']) ?></td>
                                    <td><?= htmlspecialchars($option['size']) ?></td>
                                    <td>
                                        <input type="number" name="options[<?= $option['id'] ?>][stock]" value="<?= htmlspecialchars($option['stock_amount']) ?>" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="options[<?= $option['id'] ?>][price]" value="<?= htmlspecialchars($option['price']) ?>" required>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="form_group">
                    <h3>Afbeeldingen Bewerken</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Huidige Afbeelding</th>
                                <th>Nieuwe Afbeelding</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productImages as $image): ?>
                                <tr>
                                    <td><img src="../images/product_images/<?= htmlspecialchars($image['url']) ?>" alt="Product Image" width="100"></td>
                                    <td><input type="file" name="images[<?= $image['id'] ?>]"></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="submit">Opslaan</button>
            </form>
            <?php if (isset($error)): ?>
                <p><?= $error ?></p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>