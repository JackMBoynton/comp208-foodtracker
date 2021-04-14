<?php
class request {

    // connection instance
    private $conn;

    // the table name
    private $tbl = "request";

    // table columns
    public $ProductNo;
    public $ProductBarcode;
    public $UserID;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /* POST http://comp208-foodtracker/requests/create.php

    $userID = this is the user's ID for the groceries we have to get

    This route reads all the groceries for a specific user

    */
    public function create($ProductName, $ProductBarcode, $UserID) {

        // Having to use prepare and binding here, so no SQL injection
        $stmt = $this->conn->prepare("INSERT INTO request (ProductName, ProductBarcode, UserID) VALUES (:name, :barcode, :uid)");
        
        $stmt->bindValue(':name', $ProductName, PDO::PARAM_STR);
        $stmt->bindValue(':barcode', $ProductBarcode, PDO::PARAM_STR);
        $stmt->bindValue(':uid', $UserID, PDO::PARAM_INT);

        return $stmt->execute();

    }

}