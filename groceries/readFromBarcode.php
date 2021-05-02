<?php

/*

    THIS IS AN UNAUTHENTICATED METHOD IN THE API, THERE IS NO NEED FOR IT TO HAVE AUTHENTICATION

*/

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/dbconfig.php';
include_once '../entities/grocery.php';
include_once '../entities/user.php';

$db = new dbconfig();
$conn = $db->getConnection();

$grocery = new grocery($conn);


// call read function from grocery, GET with barcode
if (isset($_POST['barcode']) && !empty($_POST['barcode'])) {

    $return = $grocery->barcodeSearch($_POST['barcode']);

    $result = array(
        "Result" => $return
    );
    
    echo json_encode($result);

} else {

    $result = array(
        "Result" => "Failed: No barcode specified"
    );

    echo json_encode($result);

}

