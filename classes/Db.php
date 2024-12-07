<?php 
class Db {
    private static $conn;

    public static function connect() { // this method is used to connect to the database
        if (self::$conn === null) { // if the connection is not yet established
            // Update the connection details for Azure MySQL
            $host = 'php-lamp-shop-server.mysql.database.azure.com';
            $dbname = 'php-lamp-shop-database'; // Correcte variabele naam
            $username = 'tgpwrugenq'; // Vervang door jouw gebruikersnaam
            $password = 'nH$DrdFPn85MIbbo'; // Vervang door jouw wachtwoord
            $port = '3306'; // Standaard MySQL-poort
            $sslCert = '../DigiCertGlobalRootCA.crt.pem';

            try {
                self::$conn = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4", // Gebruik $dbname
                    $username,
                    $password,
                    [
                        PDO::MYSQL_ATTR_SSL_CA => $sslCert,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
                return self::$conn;
            } catch (PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        } else {
            return self::$conn;
        }
    }
}
?>