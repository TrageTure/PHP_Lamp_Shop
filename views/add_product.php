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

// Haal categorieën, kleuren en maten op
$product = new Product();
$categories = $product->getAllCategories();
$options = new Options();
$allColors = $options->getAllColors();
$allSizes = $options->getAllSizes();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Valideer en voeg product toe
        $product->setTitle($_POST['title'])
                ->setCategoryId($_POST['category_id'])
                ->setDescription($_POST['description'])
                ->save();
        $productId = $product->getId();

        // Afbeeldingen toevoegen
        if (!empty($_FILES['images']['name'][0])) {
            $images = new Images();
            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                $fileName = uniqid() . "_" . basename($_FILES['images']['name'][$key]);
                move_uploaded_file($tmpName, "../images/product_images/$fileName");
                $images->addImage($productId, $fileName);
            }
        }

        // Opties toevoegen
        foreach ($_POST['colors'] as $color) {
            foreach ($_POST['sizes'] as $size) {
                $option = new Options();
                $option->setProductId($productId)
                       ->setColorId($color)
                       ->setSizeId($size)
                       ->setStockAmount($_POST['stock_amount'][$color][$size])
                       ->setPrice($_POST['price'][$color][$size])
                       ->save();
            }
        }

        echo "Product succesvol toegevoegd!";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product toevoegen</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <?php include_once('../components/nav.php')?>
    <div class="filler"></div>
    <main>
        <section class="admin_section">
            <h1>Nieuw product toevoegen</h1>
            <form method="post" enctype="multipart/form-data">
                <div class="form_group">
                    <label for="title">Titel</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form_group">
                    <label for="category_id">Categorie</label>
                    <select id="category_id" name="category_id" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form_group">
                    <label for="description">Beschrijving</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                </div>
                <div class="form_group">
                    <label for="images">Afbeeldingen (max. 4)</label>
                    <input type="file" id="images" name="images[]" multiple onchange="checkFileLimit(this)">
                </div>
                <div class="form_group">
                    <h3>Opties</h3>
                    <label>Kleuren</label>
                    <table>
                        <thead>
                            <tr>
                                <th>Kleur</th>
                                <th>Selecteer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allColors as $color): ?>
                                <tr>
                                    <td><?= htmlspecialchars($color['color_name']) ?></td>
                                    <td><input type="checkbox" name="colors[]" value="<?= $color['id'] ?>" class="color-checkbox" data-color="<?= $color['color_name']?>"></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <label>Maten</label>
                    <table>
                        <thead>
                            <tr>
                                <th>Maat</th>
                                <th>Selecteer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allSizes as $size): ?>
                                <tr>
                                    <td><?= htmlspecialchars($size['size']) ?></td>
                                    <td><input type="checkbox" name="sizes[]" value="<?= $size['id']?>" class="size-checkbox" data-size="<?= $size['size']?>"></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <h3>Voorraad en prijzen</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Kleur</th>
                                <th>Maat</th>
                                <th>Voorraad</th>
                                <th>Prijs</th>
                            </tr>
                        </thead>
                        <tbody id="stock-price-table">
                            <!-- Dynamisch gegenereerde rijen komen hier -->
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
<script>
function checkFileLimit(input) {
    if (input.files.length > 4) {
        alert("Je kunt maximaal 4 afbeeldingen uploaden.");
        input.value = ""; 
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const colorCheckboxes = document.querySelectorAll('.color-checkbox');
    const sizeCheckboxes = document.querySelectorAll('.size-checkbox');
    const stockPriceTable = document.getElementById('stock-price-table');

    function updateStockPriceTable() {
        stockPriceTable.innerHTML = ''; // Leeg de tabel

        const selectedColors = Array.from(colorCheckboxes).filter(checkbox => checkbox.checked).map(checkbox => checkbox.value); // Gebruik ID's
        const selectedSizes = Array.from(sizeCheckboxes).filter(checkbox => checkbox.checked).map(checkbox => checkbox.value); // Gebruik ID's

        selectedColors.forEach(color => {
            selectedSizes.forEach(size => {
                const color_name = document.querySelector(`.color-checkbox[value="${color}"]`).getAttribute('data-color');
                const size_name = document.querySelector(`.size-checkbox[value="${size}"]`).getAttribute('data-size');
                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${color_name}</td>
                <td>${size_name}</td>
                <td><input type="number" name="stock_amount[${color}][${size}]" placeholder="Voorraad" required></td>
                <td><input type="number" step="0.01" name="price[${color}][${size}]" placeholder="Prijs" required></td>
            `;
                stockPriceTable.appendChild(row);
            });
        });
    }

    colorCheckboxes.forEach(checkbox => checkbox.addEventListener('change', updateStockPriceTable));
    sizeCheckboxes.forEach(checkbox => checkbox.addEventListener('change', updateStockPriceTable));
});
</script>
</html>