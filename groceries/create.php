<?php

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
$user = new user($conn);

// call read function from user, POSTing with uname and pwd if they're set
if (isset($_POST['identifier']) && !empty($_POST['identifier']) && isset($_POST['password']) && !empty($_POST['password'])) {

    $return = $user->read($_POST['identifier'], $_POST['password']);
    $userCount = count($return);

    // a user was returned
    if ($userCount == 1) {
        $userID = $return[0][1];

        if (isset($_POST['barcode']) && !empty($_POST['barcode']) && isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['expiry']) && !empty($_POST['expiry'])) {
            $status = $grocery->create($_POST['barcode'], $_POST['name'], $_POST['expiry'], $userID);
        }

        // status of creating our product
        if ($status) {
            
            $result = array(
                "Result" => "Success: Grocery created"
            );
        
            echo json_encode($result);

        } else {
            
            $result = array(
                "Result" => "Failed: API error"
            );
        
            echo json_encode($result);

        }

    } else {

        $result = array(
            "Result" => "Failed: Authentication error"
        );
    
        echo json_encode($result);

    }

} else {

    $result = array(
        "Result" => "Failed: Authentication error"
    );

    echo json_encode($result);

}