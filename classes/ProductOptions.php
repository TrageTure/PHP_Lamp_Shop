<?php
include_once('Db.php');

class Options {
    private $id;
    private $colorId;
    private $sizeId;
    private $stockAmount;
    private $productId;

    public function getId() {
        return $this->id;
    }

    public function getColorId() {
        return $this->colorId;
    }

    public function setColorId($colorId) {
        $this->colorId = $colorId;
        return $this;
    }

    public function getSizeId() {
        return $this->sizeId;
    }

    public function setSizeId($sizeId) {
        $this->sizeId = $sizeId;
        return $this;
    }

    public function getStockAmount() {
        return $this->stockAmount;
    }

    public function setStockAmount($stockAmount) {
        $this->stockAmount = $stockAmount;
        return $this;
    }

    public function setProductId($productId) {
        $this->productId = $productId;
        return $this;
    }

    public function save() {
        $conn = Db::connect();
        $statement = $conn->prepare("INSERT INTO product_options (color_id, size_id, stock_amount, product_id) VALUES (:color_id, :size_id, :stock_amount, :product_id)");
        $statement->bindValue(":color_id", $this->getColorId());
        $statement->bindValue(":size_id", $this->getSizeId());
        $statement->bindValue(":stock_amount", $this->getStockAmount());
        $statement->bindValue(":product_id", $this->productId); // Voeg product_id toe
        $statement->execute();
    }

    public function getAllOptions() {
        $conn = Db::connect();
        $statement = $conn->query("SELECT * FROM product_options");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllColors() {
        $conn = Db::connect();
        $statement = $conn->query("SELECT * FROM colors");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getColorsByProductId($productId) {
        $conn = Db::connect();
        $statement = $conn->prepare("SELECT colors.id, colors.color_name FROM colors INNER JOIN product_options ON colors.id = product_options.color_id WHERE product_options.product_id = :product_id");
        $statement->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSizes() {
        $conn = Db::connect();
        $statement = $conn->query("SELECT * FROM size");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSizesByProductId($productId) {
        $conn = Db::connect();
        $statement = $conn->prepare("SELECT size.id, size.size_name FROM size INNER JOIN product_options ON size.id = product_options.size_id WHERE product_options.product_id = :product_id");
        $statement->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOptionsByProductId($productId) {
        $conn = Db::connect();
        $statement = $conn->prepare("SELECT * FROM product_options WHERE product_id = :product_id");
        $statement->bindValue(":product_id", $productId);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUniqueColorsByProductId($productId) {
        $conn = Db::connect();
        $statement = $conn->prepare("SELECT DISTINCT colors.id, colors.color_name FROM product_options JOIN colors ON product_options.color_id = colors.id WHERE product_options.product_id = :product_id");
        $statement->bindValue(":product_id", $productId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUniqueSizesByProductId($productId) {
        $conn = Db::connect();
        $statement = $conn->prepare("SELECT DISTINCT size.id, size.size FROM product_options JOIN size ON product_options.size_id = size.id WHERE product_options.product_id = :product_id");
        $statement->bindValue(":product_id", $productId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStockAmountByColorAndSize($productId, $colorId, $sizeId) {
        $conn = Db::connect();
        $statement = $conn->prepare("SELECT stock_amount FROM product_options WHERE product_id = :product_id AND color_id = :color_id AND size_id = :size_id");
        $statement->bindValue(":product_id", $productId, PDO::PARAM_INT);
        $statement->bindValue(":color_id", $colorId, PDO::PARAM_INT);
        $statement->bindValue(":size_id", $sizeId, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result !== false ? $result : null;
    }
}
?>