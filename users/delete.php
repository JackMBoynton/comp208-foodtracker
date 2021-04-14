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

            $jsonRes = new stdClass();

            $jsonRes->status = "200";
            $jsonRes->title = "User deleted.";
            $jsonRes->detail = "The user has been removed from the database.";

            echo json_encode($jsonRes);

        } else {
            
            $jsonRes = new stdClass();

            $jsonRes->status = "409";
            $jsonRes->title = "User not deleted.";
            $jsonRes->detail = "The user has not been removed from the database due to an error.";

            echo json_encode($jsonRes);

        }

    } else {

        $jsonRes = new stdClass();

        $jsonRes->status = "401";
        $jsonRes->title = "Invalid Details";
        $jsonRes->detail = "The username / email and password combination was incorrect.";

        echo json_encode($jsonRes);

    }

} else {

    $jsonRes = new stdClass();

    $jsonRes->status = "401";
    $jsonRes->title = "No Details Supplied";
    $jsonRes->detail = "The username / email and password combination was not provided, therefore, user is not authenticated.";

    echo json_encode($jsonRes);

}
