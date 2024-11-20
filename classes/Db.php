<?php 
class Db{
    private static $conn;

    public static function connect(){//this method is used to connect to the database
        if (self::$conn === null) {//if the connection is not yet established
            self::$conn = new PDO('mysql:host=localhost;dbname=LampShopXD', 'root', 'root');;//establish the connection
            return self::$conn;
        }
        else{
            return self::$conn;
        }
    }
}
?>