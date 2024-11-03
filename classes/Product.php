<?php 
include_once('Db.php');

class Product {
    private $id;
    private $categoryId;
    private $optionsId;
    private $title;
    private $price;
    private $description;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getCategoryId() {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
        return $this;
    }

    public function getOptionsId() {
        return $this->optionsId;
    }

    public function setOptionsId($optionsId) {
        $this->optionsId = $optionsId;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    public function getThumb() {
        return $this->thumb;
    }

    public function setThumb($thumb) {
        $this->thumb = $thumb;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function save() {
        try {
            $conn = Db::connect();
            $statement = $conn->prepare("INSERT INTO products (title, price, product_categories_id, description) VALUES (:title, :price, :category_id, :description)");
            $statement->bindValue(":title", $this->title);
            $statement->bindValue(":price", $this->price);
            $statement->bindValue(":category_id", $this->categoryId);
            // $statement->bindValue(":thumb", $this->thumb);
            $statement->bindValue(":description", $this->description);
            $statement->execute();
    
            $this->id = $conn->lastInsertId();
            echo "Product successfully saved!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getAllProducts() {
        $conn = Db::connect();
        $statement = $conn->query("SELECT * FROM products");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCategories() {
        $conn = Db::connect();
        $statement = $conn->query("SELECT * FROM product_categories");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductsByCategory($category_id) {
        $conn = Db::connect();
        $statement = $conn->prepare("SELECT * FROM products WHERE product_categories_id = :category_id");
        $statement->bindValue(":category_id", $category_id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($product_id) {
        $conn = Db::connect();
        $statement = $conn->prepare("SELECT * FROM products WHERE id = :product_id");
        $statement->bindValue(":product_id", $product_id);
        $statement->execute();
        $productData = $statement->fetch(PDO::FETCH_ASSOC);
        
        if ($productData) {
            $this->setId($productData['id']);
            $this->setCategoryId($productData['product_categories_id']);
            $this->setTitle($productData['title']);
            $this->setPrice($productData['price']);
            $this->setDescription($productData['description']);
            return $this; // Retourneer het huidige object
        } else {
            throw new Exception("Product not found");
        }
    }

    //functie voor verwijderen producten uit de database en de image uit de map uploads
    public function deleteProduct($product_id) {
        try {
            $conn = Db::connect();
            
            // Start een transactie
            $conn->beginTransaction();
    
            // Haal alle afbeeldingennamen op die gekoppeld zijn aan het product
            $statement = $conn->prepare("SELECT url FROM product_images WHERE product_id = :product_id");
            $statement->bindValue(":product_id", $product_id, PDO::PARAM_INT);
            $statement->execute();
            $images = $statement->fetchAll(PDO::FETCH_ASSOC);
    
            // Verwijder elke afbeelding uit het bestandssysteem
            foreach ($images as $image) {
                $filePath = "../images/product_images/" . $image['url'];
                if (file_exists($filePath)) {
                    unlink($filePath); // Verwijder de afbeelding
                }
            }
    
            // Verwijder het product zelf, wat dankzij ON DELETE CASCADE ook de gekoppelde records in images en product_options verwijdert
            $statement = $conn->prepare("DELETE FROM products WHERE id = :product_id");
            $statement->bindValue(":product_id", $product_id, PDO::PARAM_INT);
            $statement->execute();
    
            $conn->commit(); // Transactie committen na succesvolle verwijdering
    
        } catch (Exception $e) {
            $conn->rollBack(); // Terugdraaien bij een fout
            throw $e; 
        }
    }
}
?>