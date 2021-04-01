<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/dbconfig.php';
include_once '../entities/product.php';

$db = new dbconfig();
$conn = $db->getConnection();

$product = new product($conn);

// calling our read method from the product class
$stmt = $product->read();
$count = $stmt->rowCount();

if ($count > 0) {

    $products = array();
    $products["body"] = array();
    $products["count"] = $count;

    // whilst we actually have rows to go through
    while ($row = $stmt->fetch()) {

        extract($row);

        $p = array(
            "ProductNo" => $ProductNo,
            "Barcode" => $Barcode,
            "Name" => $Name
        );

        array_push($products['body'], $p);

    }

    echo json_encode($products);

} else {

    echo json_encode(
        array("body" => array(), "count" => 0)
    );

}