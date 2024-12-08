<?php 
include_once('Db.php');

class Review{
    private $id;
    private $user_id;
    private $product_id;
    private $rating;
    private $comment;
    private $created_at;

    public function __construct($user_id, $product_id, $rating, $comment, $created_at = null, $id = null) {
        $this->id = $id; 
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->created_at = $created_at;
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

    public function getProduct_id()
    {
        return $this->product_id;
    }

    public function setProduct_id($product_id)
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreated_at()
    {
        return $this->created_at;
    }

    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }
    
    //functie om review toe te voegen maar kan alleen als de gebruiker het product al heeft gekocht
    public function save() {
        if (!self::hasPurchasedProduct($this->user_id, $this->product_id)) {
            throw new Exception("Je kunt geen review schrijven voor een product dat je niet hebt gekocht.");
        }
    
        $conn = Db::connect();
        $statement = $conn->prepare("INSERT INTO reviews (user_id, product_id, stars_amount, review) VALUES (:user_id, :product_id, :rating, :review)");
        $statement->bindValue(":user_id", $this->user_id);
        $statement->bindValue(":product_id", $this->product_id);
        $statement->bindValue(":rating", $this->rating);
        $statement->bindValue(":review", $this->comment);
        $statement->execute();
    }

    public static function hasPurchasedProduct($user_id, $product_id) {
        $conn = Db::connect();
    
        $statement = $conn->prepare("
            SELECT ol.id 
            FROM orders o
            JOIN order_lines ol ON o.id = ol.order_id
            WHERE o.user_id = :user_id AND ol.product_id = :product_id
            LIMIT 1
        ");
        $statement->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $statement->bindValue(":product_id", $product_id, PDO::PARAM_INT);
        $statement->execute();
    
        return $statement->fetch(PDO::FETCH_ASSOC) !== false;
    }

    //functie om alle reviews op te halen door middel van een product_id
    public static function getReviewsByProductId($product_id, $limit, $offset) {
        $conn = Db::connect();
        $statement = $conn->prepare("
            SELECT r.*, u.first_name, u.last_name, u.profile_pic
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.product_id = :product_id
            ORDER BY r.date_time_review DESC
            LIMIT :limit OFFSET :offset
        ");
        $statement->bindValue(":product_id", $product_id, PDO::PARAM_INT);
        $statement->bindValue(":limit", $limit, PDO::PARAM_INT);
        $statement->bindValue(":offset", $offset, PDO::PARAM_INT);
        $statement->execute();
    
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>