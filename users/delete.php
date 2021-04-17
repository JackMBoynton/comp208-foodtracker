<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/dbconfig.php';
include_once '../entities/user.php';

$db = new dbconfig();
$conn = $db->getConnection();

$user = new user($conn);

// check the post variables
// make sure that all fields are set and non are empty
if (isset($_POST['identifier']) && !empty($_POST['identifier']) && isset($_POST['password']) && !empty($_POST['password'])) {
    
    $return = $user->read($_POST['identifier'], $_POST['password']);
    $userCount = count($return);

    // a user was returned
    if ($userCount == 1) {
        $userID = $return[0][1];

        // we either get back a JSON object of groceries, or not
        $status = $user->delete($userID);

        // status of creating our product
        if ($status) {

            $result = array(
                "Result" => "Success: User deleted"
            );
        
            echo json_encode($result);

        } else {
            
            $result = array(
                "Result" => "Failed: Database error"
            );
        
            echo json_encode($result);

        }

    } else {

        $result = array(
            "Result" => "Failed: Authentication incorrect username/password"
        );
    
        echo json_encode($result);

    }

} else {

    $result = array(
        "Result" => "Failed: Authentication incorrect username/password"
    );

    echo json_encode($result);

}
