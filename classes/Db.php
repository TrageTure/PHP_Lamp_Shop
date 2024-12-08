<?php 
class Db {
    private static $conn;

    public static function connect() { // this method is used to connect to the database
        if (self::$conn === null) { // if the connection is not yet established
            // Update the connection details for Azure MySQL
            $host = getenv('DB_HOST');
            $dbname = getenv('DB_DATABASE');
            $username = getenv('DB_USERNAME');
            $password = getenv('DB_PASSWORD');
            $port = getenv('DB_PORT');
            $sslCert = getenv('SSL_CERT');

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