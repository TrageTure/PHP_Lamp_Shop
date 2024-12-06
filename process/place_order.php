<?php 
session_start();

include_once('../classes/Order.php');
include_once('../classes/Orderline.php');
include_once('../classes/User.php');

$order = new Order();
$orderline = new Orderline();

$user = new User();
$result = $user->getAllFromEmail($_SESSION['email']);
$user_id = $result['id'];

$order->createOrder($user_id, $_POST['full_price'], $_POST['delivery_option']);


?>