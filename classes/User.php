<?php 
include_once('Db.php');
class User{
    private $firstname;
    private $lastname;
    private $email;
    private $password;

    public function getFirstname()
    {   
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {   
        if (empty($firstname)) {
            throw new Exception("Firstname cannot be empty");
        }
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {   
        if (empty($lastname)) {
            throw new Exception("Lastname cannot be empty");
        }
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail()
    {   
        return $this->email;
    }

    public function setEmail($email)
    {   
        if (empty($email)) {
            throw new Exception("Email cannot be empty");
        }
        $this->email = $email;

        return $this;
    }

    public function getPassword()
    {   
        return $this->password;
    }

    public function setPassword($password)
    {   
        if (empty($password)) {
            throw new Exception("Password cannot be empty");
        }
        $cost = 12;
        $this->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => $cost]);
        return $this;
    }

    public function save() {
        $conn = Db::connect();
    
        // Controleer of de gebruiker al bestaat
        if ($this->userExists()) {
            throw new Exception('Email already in use');
        } 
    
        $statement = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:firstname, :lastname, :email, :password)");
        $statement->bindParam(':firstname', $this->firstname);
        $statement->bindParam(':lastname', $this->lastname);
        $statement->bindParam(':email', $this->email);
        $statement->bindParam(':password', $this->password);
        $statement->execute();
    
        // Redirect na succesvolle registratie
        header('Location: login.php');
        exit();
    }
    
    // Aparte methode om te controleren of een gebruiker al bestaat
    private function userExists() {
        $conn = Db::connect();
        $statement = $conn->prepare('SELECT * FROM users WHERE email = :email');
        $statement->bindParam(':email', $this->email);
        $statement->execute();
        return $statement->fetch() !== false;
    }

    public function loginAvailable($password){
        $conn = Db::connect();
        $statement = $conn->prepare('SELECT * FROM users WHERE email = :email');
        $statement->bindParam(':email', $this->email);
        $statement->execute();
        $user = $statement->fetch();
    
        if ($user && isset($user['PASSWORD']) && !is_null($user['PASSWORD'])) {
            if (password_verify($password, $user['PASSWORD'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $this->email;
                return true;
            } else {
                throw new Exception('Invalid password');
            }
        } else {
            throw new Exception('User not found');
        }
    }

    public function getAllFromEmail($email){
        $conn = Db::connect();
        $statement = $conn->prepare('SELECT * FROM users WHERE email = :email');
        $statement->bindParam(':email', $email);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC); 
        return $user;
    }

    //een functie om wachtwoord te veranderen waar de gebruiker eerst zen oude passwoord moet ingeven
    public function changePassword($oldPassword, $newPassword) {
        if (!$this->email) {
            throw new Exception('Email address is not set');
        }
    
        $conn = Db::connect();
    
        try {
            $conn->beginTransaction();
            $statement = $conn->prepare('SELECT PASSWORD FROM users WHERE email = :email');
            $statement->bindParam(':email', $this->email);
            $statement->execute();
            $user = $statement->fetch();
    
            if ($user && isset($user['PASSWORD']) && !is_null($user['PASSWORD'])) {
                if (password_verify($oldPassword, $user['PASSWORD'])) {
                    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT, ['cost' => 12]);
    
                    $updateStatement = $conn->prepare('UPDATE users SET PASSWORD = :password WHERE email = :email');
                    $updateStatement->bindParam(':password', $newPasswordHash);
                    $updateStatement->bindParam(':email', $this->email);
    
                    // Gebruik een transactie
                    $updateStatement->execute();
                    $conn->commit();
    
                    return true;
                } else {
                    throw new Exception('Onjuist huidig passwoord');
                    $result['div'] = 'current_password';
                }
            } else {
                throw new Exception('User not found');
            }
        } catch (Exception $e) {
            $conn->rollBack(); 
            throw $e;
        }
    }

    public function updateBalance($user_id, $new_balance) {
        $conn = Db::connect();
        $statement = $conn->prepare('UPDATE users SET currency = :new_balance WHERE id = :user_id');
        $statement->bindParam(':new_balance', $new_balance);
        $statement->bindParam(':user_id', $user_id);
        $statement->execute();
    }

    //functie om alle gebruikers op te halen door middel van een user_id
    public function getAllFromId($user_id){
        $conn = Db::connect();
        $statement = $conn->prepare('SELECT * FROM users WHERE id = :user_id');
        $statement->bindParam(':user_id', $user_id);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC); 
        return $user;
    }
}
?>