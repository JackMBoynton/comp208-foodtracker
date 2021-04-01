<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/dbconfig.php';
include_once '../entities/product.php';

$db = new dbconfig();
$conn = $db->getConnection();

$product = new product($conn);

// get the expected post variables
$barcode = $_POST["barcode"];
$name = $_POST["name"];

$status = FALSE;

// run our insert query
if (!empty($barcode) && !empty($name)) {
    $status = $product->create($barcode, $name);
}

// status of creating our product
if ($status) {
    echo '{';
        echo '"message:": Product was created."';
    echo '}';
} else {
    echo '{';
        echo '"message:": Unable to create product."';
    echo '}';
}
