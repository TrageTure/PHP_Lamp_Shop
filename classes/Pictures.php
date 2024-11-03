<?php 
include_once('Db.php');

class Images {
    private $id;
    private $product_id;
    private $url;

    // Ophalen van alle afbeeldingen voor een bepaald product

    public function getImagesByProductId($product_id) {
        $conn = Db::connect();
        $statement = $conn->prepare("SELECT url FROM product_images WHERE product_id = :product_id ORDER BY id ASC");
        $statement->bindValue(":product_id", $product_id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getThumbnailByProductId($product_id) {
        $conn = Db::connect();
        $statement = $conn->prepare("SELECT url FROM product_images WHERE product_id = :product_id ORDER BY id ASC LIMIT 1");
        $statement->bindValue(":product_id", $product_id, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['url'] : null;
    }

    // Afbeelding toevoegen aan een product
    public function addImage($productId, $imageName) {
        $conn = Db::connect();
        $statement = $conn->prepare("INSERT INTO product_images (product_id, url) VALUES (:product_id, :file_name)");
        $statement->bindValue(":product_id", $productId);
        $statement->bindValue(":file_name", $imageName);
        $statement->execute();
    }

    // Afbeelding verwijderen
    public function deleteImage($image_id) {
        $conn = Db::connect();
        
        $statement = $conn->prepare("SELECT url FROM product_images WHERE id = :image_id");
        $statement->bindValue(":image_id", $image_id, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $filePath = "../images/product_images/" . $result['url'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $statement = $conn->prepare("DELETE FROM product_images WHERE id = :image_id");
            $statement->bindValue(":image_id", $image_id, PDO::PARAM_INT);
            $statement->execute();
        }
    }
}
?>