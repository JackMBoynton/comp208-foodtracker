<?php
class grocery {

    // connection instance
    private $conn;

    // the table name
    private $tbl = "grocery";

    // table columns
    public $GroceryNo;
    public $Barcode;
    public $Name;
    public $ExpiryDate;
    public $UserID;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /* POST http://comp208-foodtracker/groceries/create.php

    $barcode = this is the barcode of the grocery we will be creating
    $name = the name of the grocery we will be creating
    $expiryDate = the expiry of the grocery being created
    $userID = the id of the user the grocery is being created on behalf of

    This route creates a grocery via the iPhone application scanning / form.

    */
    public function create($barcode, $name, $expiryDate, $userID) {

        // Having to use prepare and binding here, so no SQL injection
        $stmt = $this->conn->prepare("INSERT INTO grocery (Barcode, Name, ExpiryDate, UserID) VALUES (:barcode, :name, :ExpiryDate, :UserID)");
        $stmt->bindValue(':barcode', $barcode, PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':name', $expiryDate, PDO::PARAM_STR);
        $stmt->bindValue(':name', $userID, PDO::PARAM_INT);

        return $stmt->execute();

    }

    /* POST http://comp208-foodtracker/groceries/readUserGroceries.php

    $userID = this is the user's ID for the groceries we have to get

    This route reads all the groceries for a specific user

    */
    public function readAll($userID) {

        // No preparing or binding needed, as no user input is present in SQL
        $query = "SELECT * FROM " . $this->tbl . " WHERE UserID = " . $userID;
        return $this->conn->exec($query);

    }

    /* GET http://comp208-foodtracker/groceries/read.php

    $groceryNo = this is the grocery number for the grocery we have to get

    This route reads a specified grocery

    */
    public function read($groceryNo) {

        // No preparing or binding needed, as no user input is present in SQL
        $query = "SELECT * FROM " . $this->tbl . " WHERE GroceryNo = " . $groceryNo;
        return $this->conn->exec($query);

    }

    /* POST http://comp208-foodtracker/groceries/update.php

    $userID = this is the user's ID for the groceries we have to get

    This route reads all the groceries for a specific user

    */
    public function update() {

    }

    public function delete() {

    }

}