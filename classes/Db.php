<?php 
class Db {
    private static $conn;

    public static function connect() { // this method is used to connect to the database
        if (self::$conn === null) { // if the connection is not yet established
            try {
                // Update the connection details for Azure MySQL
                $host = 'php-lamp-shop-server.mysql.database.azure.com';
                $dbname = 'php-lamp-shop-database';
                $username = 'tgprwugenq'; // Vervang door jouw gebruikersnaam
                $password = 'nH$DrdFPn85MIbbo'; // Vervang door jouw wachtwoord
                $port = '3306'; // Standaard MySQL-poort

                // Establish the connection with SSL enabled
                $dsn = "mysql:host=$host;dbname=$dbname;port=$port;charset=utf8;sslmode=require";
                self::$conn = new PDO($dsn, $username, $password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return self::$conn;
            } catch (PDOException $e) {
                // Error handling
                die("Database connection failed: " . $e->getMessage());
            }
        } else {
            return self::$conn;
        }
    }
}
?>