<?php

class Order {
    private $id;
    private $user_id;
    private $order_date;
    private $full_price;
    private $delivery_option;

    public function __construct($id, $user_id, $order_date, $full_price, $delivery_option) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->order_date = $order_date;
        $this->full_price = $full_price;
        $this->delivery_option = $delivery_option;
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
}
?>