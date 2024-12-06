<?php
include_once('Db.php');
class Orderline {
    private $id;
    private $order_id;
    private $product_id;
    private $amount;
    private $price_per_unit;

    public function __construct($id, $order_id, $product_id, $amount, $price_per_unit) {
        $this->id = $id;
        $this->order_id = $order_id;
        $this->product_id = $product_id;
        $this->amount = $amount;
        $this->price_per_unit = $price_per_unit;
    }
    
    //? Getters
    public function getId() {
        return $this->id;
    }

    public function getOrderId() {
        return $this->order_id;
    }

    public function getProductId() {
        return $this->product_id;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getPricePerUnit() {
        return $this->price_per_unit;
    }

    //? Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setOrderId($order_id) {
        $this->order_id = $order_id;
    }

    public function setProductId($product_id) {
        $this->product_id = $product_id;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }

    public function setPricePerUnit($price_per_unit) {
        $this->price_per_unit = $price_per_unit;
    }

    public function save() {
        try {
            $conn = Db::getConnection();
            $statement = $conn->prepare("INSERT INTO orderlines (order_id, product_id, amount, price_per_unit) VALUES (:order_id, :product_id, :amount, :price_per_unit)");
            $order_id = $this->getOrderId();
            $product_id = $this->getProductId();
            $amount = $this->getAmount();
            $price_per_unit = $this->getPricePerUnit();
            $statement->bindParam(":order_id", $order_id);
            $statement->bindParam(":product_id", $product_id);
            $statement->bindParam(":amount", $amount);
            $statement->bindParam(":price_per_unit", $price_per_unit);
            $statement->execute();
        } catch (Throwable $t) {
            return $t->getMessage();
        }
    }
    
    
}
?>