<?php 
class Product {
    private $id;
    private $categoryId;
    private $optionsId;
    private $title;
    private $price;
    private $thumb;
    private $description;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function getThumb()
    {
        return $this->thumb;
    }

    public function setThumb($thumb)
    {
        $this->thumb = $thumb;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
?>
