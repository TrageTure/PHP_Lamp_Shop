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

    public function save(){
        $conn = Db::connect();
        $statement = $conn->prepare("insert into users (first_name, last_name, email, password) values (:firstname, :lastname, :email, :password)");
        $statement->bindParam(':firstname', $this->firstname);
        $statement->bindParam(':lastname', $this->lastname);
        $statement->bindParam(':email', $this->email);
        $statement->bindParam(':password', $this->password);
        $statement->execute();
    }

    public function loginAvailable($password){
        $conn = Db::connect();
        $statement = $conn->prepare('SELECT * FROM users WHERE email = :email');
        $statement->bindParam(':email', $this->email);
        $statement->execute();
        $user = $statement->fetch();
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
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
}
?>