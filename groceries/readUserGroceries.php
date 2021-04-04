<?php

header("Content-Type: application/json; charset=UTF-8");

include_once '../config/dbconfig.php';
include_once '../entities/grocery.php';

$db = new dbconfig();
$conn = $db->getConnection();

// get the user ID
$userID = 0;

if (isset($_GET["userID"])) {
    $userID = $_GET["userID"];
} else {
    echo '{';
    echo '"message:": Error! You need to specify a User ID as parameter userID in the GET request."';
    echo '}';
}

$grocery = new grocery($conn);

$stmt = $grocery->readAll($userID);
$count = $stmt->rowCount();

if (count > 0) {

    // setting up the JSON response
    $groceries = array();
    $groceries["body"] = array();
    $groceries["count"] = $count;

    while ($row = $stmt->fetch()) {

        extract($row);

        $currentGrocery = array(
            "GroceryNo" => $GroceryNo,
            "Barcode" => $Barcode,
            "Name" => $Name,
            "ExpiryDate" => $ExpiryDate,
            "UserID" => $UserID
        );

        array_push($groceries["body"], $currentGrocery);

    }

}

