<?php
class product {

    // connection instance
    private $conn;

    // the table name
    private $tbl = "product";

    // table columns
    public $Barcode;
    public $Name;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($Barcode, $Name) {
        $stmt = $this->conn->prepare("INSERT INTO product (Barcode, Name) VALUES (:barcode, :name)");
        $stmt->bindValue(':barcode', $Barcode, PDO::PARAM_STR);
        $stmt->bindValue(':name', $Name, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->tbl;

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function update() {

    }

    public function delete() {

    }

}