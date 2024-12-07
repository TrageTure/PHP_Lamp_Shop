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
            $conn = Db::connect();
            $statement = $conn->prepare("
                INSERT INTO order_lines (order_id, product_id, amount, price_per_unit) 
                VALUES (:order_id, :product_id, :amount, :price_per_unit)
            ");
    
            $statement->bindValue(':order_id', $this->order_id, PDO::PARAM_INT);
            $statement->bindValue(':product_id', $this->product_id, PDO::PARAM_INT);
            $statement->bindValue(':amount', $this->amount, PDO::PARAM_INT);
            $statement->bindValue(':price_per_unit', $this->price_per_unit, PDO::PARAM_STR);
    
            $statement->execute();
        } catch (PDOException $e) {
            throw new Exception("Fout bij het opslaan van de orderregel: " . $e->getMessage());
        }
    }
    
    //alles van orderline door de orderid ophalen
    public static function getOrderlinesByOrderId($order_id) {
        try {
            $conn = Db::connect();
            $statement = $conn->prepare("SELECT * FROM order_lines WHERE order_id = :order_id");
            $statement->bindValue(':order_id', $order_id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Fout bij het ophalen van de orderregels: " . $e->getMessage());
        }
    }
}
?>