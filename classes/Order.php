<?php
include_once('Db.php');
class Order {
    private $id;
    private $user_id;
    private $order_date;
    private $full_price;
    private $delivery_option;
    private $delivery_location_id;

    public function __construct($id, $user_id, $order_date, $full_price, $delivery_option, $delivery_location_id) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->order_date = $order_date;
        $this->full_price = $full_price;
        $this->delivery_option = $delivery_option;
        $this->delivery_location_id = $delivery_location_id;
    }

    //? Getters
    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getOrderDate() {
        return $this->order_date;
    }

    public function getFullPrice() {
        return $this->full_price;
    }

    public function getDeliveryOption() {
        return $this->delivery_option;
    }

    public function getDeliveryLocationId() {
        return $this->delivery_location_id;
    }

    //? Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setOrderDate($order_date) {
        $this->order_date = $order_date;
    }

    public function setFullPrice($full_price) {
        $this->full_price = $full_price;
    }

    public function setDeliveryOption($delivery_option) {
        $this->delivery_option = $delivery_option;
    }

    public function setDeliveryLocationId($delivery_location_id) {
        $this->delivery_location_id = $delivery_location_id;
    }

    public function createOrder($user_id, $full_price, $delivery_option, $delivery_location_id) {
        try {
            $conn = Db::connect();
            $statement = $conn->prepare("
                INSERT INTO orders (user_id, full_price, delivery_option, delivery_location_id) 
                VALUES (:user_id, :full_price, :delivery_option, :delivery_location)
            ");
    
            $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $statement->bindValue(':full_price', $full_price, PDO::PARAM_STR);
            $statement->bindValue(':delivery_option', $delivery_option, PDO::PARAM_INT);
            $statement->bindValue(':delivery_location', $delivery_location_id, PDO::PARAM_INT);
    
            $statement->execute();
    
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Fout bij het aanmaken van de bestelling: " . $e->getMessage());
        }
    }

    public static function getAllOrdersById($user_id) {
        try {
            $conn = Db::connect();
            //van nieuwste naar oudste
            $statement = $conn->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_date DESC");
            $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $statement->execute();
    
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Fout bij het ophalen van de bestellingen: " . $e->getMessage());
        }
    }
}
?>