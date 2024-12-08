<?php
session_start();
include_once('../classes/Product.php');
include_once('../classes/ProductOptions.php');
include_once('../classes/Pictures.php');
include_once('../classes/User.php');

// Zorg dat de gebruiker is ingelogd en admin is
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

// Fetch alle producten
$productInstance = new Product();
$products = $productInstance->getAllProducts();
$outofstock = $productInstance->getOutOfStockProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <title>Producten beheren</title>
</head>
<body>
    <?php include_once('../components/nav.php')?>
    <div class="filler"></div>
    <h1>Producten beheren</h1>

    <!-- Overzicht van alle producten -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titel</th>
                <th>Categorie</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['id']) ?></td>
                    <td><?= htmlspecialchars($product['title']) ?></td>
                    <td><?= htmlspecialchars($product['product_categories_id']) ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn edit">Bewerken</a>
                        <form action="delete_product.php" method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button type="submit" class="btn verwijder">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h1>Geen stock meer</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titel</th>
                <th>Categorie</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($outofstock as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['id']) ?></td>
                    <td><?= htmlspecialchars($product['title']) ?></td>
                    <td><?= htmlspecialchars($product['product_categories_id']) ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn edit">Bewerken</a>
                        <form action="delete_product.php" method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button type="submit" class="btn verwijder">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Knop om nieuw product toe te voegen -->
    <a href="add_product.php" class="btn add">Nieuw product toevoegen</a>
</body>
</html>