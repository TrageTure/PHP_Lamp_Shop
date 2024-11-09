<?php

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
}
?>