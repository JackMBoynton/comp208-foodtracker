<?php
class request {

    // connection instance
    private $conn;

    // the table name
    private $tbl = "request";

    // table columns


    public function __construct($conn) {
        $this->conn = $conn;
    }

    /* POST http://comp208-foodtracker/requests/create.php

    $userID = this is the user's ID for the groceries we have to get

    This route reads all the groceries for a specific user

    */
    public function create($ProductName, $UserID) {

        // Having to use prepare and binding here, so no SQL injection
        $stmt = $this->conn->prepare("INSERT INTO grocery (Barcode, Name, ExpiryDate, UserID) VALUES (:barcode, :name, :ExpiryDate, :UserID)");
        $stmt->bindValue(':barcode', $barcode, PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':name', $expiryDate, PDO::PARAM_STR);
        $stmt->bindValue(':name', $userID, PDO::PARAM_INT);

        return $stmt->execute();

    }

}