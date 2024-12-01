<?php 
    include_once('Db.php');

    class Deliverylocation{
        private $id;
        private $user_id;
        private $street;
        private $number;
        private $city;
        private $postal_code;
        private $country;
        private $is_active;
        private $adress_naam;

        public function __construct($user_id, $street, $number, $city, $postal_code, $country, $is_active, $adress_naam, $id = null) {
            $this->id = $id; // Kan null blijven voor nieuwe records
            $this->user_id = $user_id;
            $this->street = $street;
            $this->number = $number;
            $this->city = $city;
            $this->postal_code = $postal_code;
            $this->country = $country;
            $this->is_active = $is_active;
            $this->adress_naam = $adress_naam;
        }

        public function getId()
        {
                return $this->id;
        }

        public function setId($id)
        {
                $this->id = $id;

                return $this;
        }

        public function getUser_id()
        {
                return $this->user_id;
        }

        public function setUser_id($user_id)
        {
                $this->user_id = $user_id;

                return $this;
        }

        public function getStreet()
        {
                return $this->street;
        }

        public function setStreet($street)
        {
                $this->street = $street;

                return $this;
        }

        public function getNumber()
        {
                return $this->number;
        }

        public function setNumber($number)
        {
                $this->number = $number;

                return $this;
        }

        public function getCity()
        {
                return $this->city;
        }

        public function setCity($city)
        {
                $this->city = $city;

                return $this;
        }

        public function getPostal_code()
        {
                return $this->postal_code;
        }

        public function setPostal_code($postal_code)
        {
                $this->postal_code = $postal_code;

                return $this;
        }

        public function getCountry()
        {
                return $this->country;
        }

        public function setCountry($country)
        {
                $this->country = $country;

                return $this;
        }

        public function getIs_active()
        {
                return $this->is_active;
        }

        public function setIs_active($is_active)
        {
                $this->is_active = $is_active;

                return $this;
        }

        public function getAdress_naam()
        {
                return $this->adress_naam;
        }

        public function setAdress_naam($adress_naam)
        {
                $this->adress_naam = $adress_naam;

                return $this;
        }

        public function save() {
            $conn = Db::connect();
            $statement = $conn->prepare("
                INSERT INTO delivery_locations (user_id, street_name, house_number, city, postal_code, country, is_active_adress, adress_naam)
                VALUES (:user_id, :street, :number, :city, :postal_code, :country, :is_active, :adress_naam)
            ");
            $user_id = $this->getUser_id();
            $street = $this->getStreet();
            $number = $this->getNumber();
            $city = $this->getCity();
            $postal_code = $this->getPostal_code();
            $country = $this->getCountry();
            $is_active = $this->getIs_active(); 
            $adress_naam = $this->getAdress_naam();
        
            $statement->bindValue(":user_id", $user_id);
            $statement->bindValue(":street", $street);
            $statement->bindValue(":number", $number);
            $statement->bindValue(":city", $city);
            $statement->bindValue(":postal_code", $postal_code);
            $statement->bindValue(":country", $country);
            $statement->bindValue(":is_active", $is_active);
            $statement->bindValue(":adress_naam", $adress_naam);
        
            return $statement->execute();
        }

        //edit delivery location
        public static function editDeliveryLocation($id, $user_id, $street, $number, $city, $postal_code, $country, $adress_naam) {
            $conn = Db::connect();
            $statement = $conn->prepare("update delivery_locations set street_name = :street, house_number = :number, city = :city, postal_code = :postal_code, country = :country ,adress_naam = :adress_naam where id = :id and user_id = :user_id");
            $statement->bindValue(":id", $id);
            $statement->bindValue(":user_id", $user_id);
            $statement->bindValue(":street", $street);
            $statement->bindValue(":number", $number);
            $statement->bindValue(":city", $city);
            $statement->bindValue(":postal_code", $postal_code);
            $statement->bindValue(":country", $country);
            $statement->bindValue(":adress_naam", $adress_naam);
            $result = $statement->execute();
            return $result;
        }

        public static function getDeliveryLocations($user_id) {
            $conn = Db::connect();
            $statement = $conn->prepare("select * from delivery_locations where user_id = :user_id order by id desc");
            $statement->bindValue(":user_id", $user_id);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        //functie voor de isActieve te updaten
        public static function updateActive($id, $user_id) {
            $conn = Db::connect();
            $statement = $conn->prepare("update delivery_locations set is_active_adress = 0 where user_id = :user_id");
            $statement->bindValue(":user_id", $user_id);
            $statement->execute();
            $statement = $conn->prepare("update delivery_locations set is_active_adress = 1 where id = :id");
            $statement->bindValue(":id", $id);
            $statement->execute();
        }

        //functie voor de isActive te krijgen
        public static function getActive($user_id) {
            $conn = Db::connect();
            $statement = $conn->prepare("select * from delivery_locations where user_id = :user_id and is_active_adress = 1");
            $statement->bindValue(":user_id", $user_id);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        //functie voor de adress te verwijderen
        public static function deleteAdress($id, $user_id) {
            $conn = Db::connect();
            $statement = $conn->prepare("delete from delivery_locations where id = :id and user_id = :user_id");
            $statement->bindValue(":id", $id);
            $statement->bindValue(":user_id", $user_id);
            $statement->execute();
        }

        //functie voor laatste adres te krijgen
        public static function getLastAdress($user_id) {
            $conn = Db::connect();
            $statement = $conn->prepare("select * from delivery_locations where user_id = :user_id order by id desc limit 1");
            $statement->bindValue(":user_id", $user_id);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
    }

?>