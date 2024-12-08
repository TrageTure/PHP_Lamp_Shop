<?php 
include_once('Db.php');

class Product {
    private $id;
    private $categoryId;
    private $optionsId;
    private $title;
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
            $statement = $conn->prepare("INSERT INTO products (title, product_categories_id, description) VALUES (:title, :category_id, :description)");
            $statement->bindValue(":title", $this->title);
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
        $statement = $conn->prepare("
            SELECT p.*, MIN(po.price) AS min_price
            FROM products p
            LEFT JOIN product_options po ON p.id = po.product_id
            GROUP BY p.id
        ");
        $statement->execute();
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
        $statement = $conn->prepare("
            SELECT p.*, MIN(po.price) AS min_price
            FROM products p
            LEFT JOIN product_options po ON p.id = po.product_id
            WHERE p.product_categories_id = :category_id
            GROUP BY p.id
        ");
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

    public function getRandomProducts() {
        $conn = Db::connect();
        $statement = $conn->prepare("SELECT id, title, description, (SELECT MIN(price) FROM product_options WHERE product_id = products.id) AS min_price FROM products ORDER BY RAND() LIMIT 10");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    //categorie op basis van product_id
    public function getCategoryByProductId($product_id) {
        $conn = Db::connect();
        $statement = $conn->prepare("SELECT product_categories_id FROM products WHERE id = :product_id");
        $statement->bindValue(":product_id", $product_id);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $conn = Db::connect();
    
        // Zorg ervoor dat er een ID is ingesteld, anders gooien we een exception
        if (!$this->id) {
            throw new Exception("Geen product ID gevonden voor update.");
        }
    
        // Voer een UPDATE uit op basis van het bestaande ID
        $statement = $conn->prepare("
            UPDATE products 
            SET title = :title, product_categories_id = :category_id, description = :description 
            WHERE id = :id
        ");
        $statement->bindValue(":title", $this->title);
        $statement->bindValue(":category_id", $this->categoryId);
        $statement->bindValue(":description", $this->description);
        $statement->bindValue(":id", $this->id, PDO::PARAM_INT);
        $statement->execute();
    }

    //functie voor het ophalen van de producten op basis van de zoekterm er wordt gezocht op categorie naam kleur en product naam
    public function searchProducts($search) {
        $conn = Db::connect();
        $statement = $conn->prepare("
            SELECT p.*, MIN(po.price) AS min_price
            FROM products p
            LEFT JOIN product_options po ON p.id = po.product_id
            LEFT JOIN product_categories pc ON p.product_categories_id = pc.id
            LEFT JOIN colors c ON po.color_id = c.id
            WHERE p.title LIKE :search
            OR pc.title LIKE :search
            OR c.color_name LIKE :search
            GROUP BY p.id
        ");
        $statement->bindValue(":search", "%$search%");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>