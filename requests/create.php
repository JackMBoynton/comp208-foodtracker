<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/dbconfig.php';
include_once '../entities/request.php';
include_once '../entities/user.php';

$db = new dbconfig();
$conn = $db->getConnection();

$request = new request($conn);
$user = new user($conn);

// check the post variables
// make sure that all fields are set and non are empty

// call read function from user, POSTing with uname and pwd if they're set
if (isset($_POST['identifier']) && !empty($_POST['identifier']) && isset($_POST['password']) && !empty($_POST['password'])) {

    $return = $user->read($_POST['identifier'], $_POST['password']);
    $userCount = count($return);

    // a user was returned
    if ($userCount == 1) {
        $userID = $return[0][1];

        
        if (isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['barcode']) && !empty($_POST['barcode'])) {
            
            $status = $request->create($_POST['name'], $_POST['barcode'], $userID);

        }

        // status of creating our product
        if ($status) {
            
            $result = array(
                "Result" => "Success: Request submitted for approval"
            );
        
            echo json_encode($result);

        } else {
            
            $result = array(
                "Result" => "Failed: Request submitting failed"
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