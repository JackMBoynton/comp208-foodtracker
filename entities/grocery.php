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
        $stmt = $this->conn->prepare("INSERT INTO grocery (Barcode, Name, ExpiryDate, UserID) VALUES (:barcode, :name, :expiryDate, :userID)");
        $stmt->bindValue(':barcode', $barcode, PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':expiryDate', $expiryDate, PDO::PARAM_STR);
        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);

        return $stmt->execute();

    }

    /* POST http://comp208-foodtracker/groceries/readUserGroceries.php

    $userID = this is the user's ID for the groceries we have to get

    This route reads all the groceries for a specific user

    */
    public function readAll($userID) {

        // we need to grab the
        $stmt = $this->conn->prepare("SELECT GroceryNo, Name, ExpiryDate FROM grocery WHERE UserID = :uid");

        $stmt->bindValue(':uid', $userID, PDO::PARAM_INT);

        // execute query
        $stmt->execute();

        // array to return
        $groceries = array();

        $groceriesCount = $stmt->rowCount();

        if ($groceriesCount > 0) {
            
            $groceries['groceries'] = array();
            $groceries['count'] = $groceriesCount;

            // while we have rows
            while ($row = $stmt->fetch()) {

                // extract the row
                extract($row);

                $currentGrocery = array(
                    "GroceryNo" => $GroceryNo,
                    "Name" => $Name,
                    "ExpiryDate" => $ExpiryDate
                );

                array_push($groceries['groceries'], $currentGrocery);

            }

        } else {

            $groceries['groceries'] = array();
            $groceries['count'] = 0;

        }

        return $groceries;

    }

    /* POST http://comp208-foodtracker/groceries/update.php

    $groceryNo = this is the groceries ID for updating

    This route reads all the groceries for a specific user

    */
    public function update($groceryNo, $name, $expiryDate) {

        // create statement and hashed password from param
        $stmt = $this->conn->prepare("UPDATE grocery SET Name = :name, ExpiryDate = :exp WHERE GroceryNo = :groceryNo");

        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':exp', $expiryDate, PDO::PARAM_STR);
        $stmt->bindValue(':groceryNo', $groceryNo, PDO::PARAM_INT);

        return $stmt->execute();

    }

    /* POST http://comp208-foodtracker/groceries/update.php

    $groceryNo = this is the groceries ID for deleting

    This route reads all the groceries for a specific user

    */
    public function delete($groceryNo) {

        $stmt = $this->conn->prepare("DELETE FROM grocery WHERE GroceryNo = :groceryNo");

        $stmt->bindValue(':groceryNo', $groceryNo, PDO::PARAM_INT);

        return $stmt->execute();

    }

}