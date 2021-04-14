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
            
            // create the json response object
            $jsonRes = array();
            $jsonRes['status'] = new stdClass();
            $jsonRes['data'] = new stdClass();

            // status in response
            $jsonRes['status']->success = "true";
            $jsonRes['status']->code = "201";

            $jsonRes['data']->type = "Grocery";
            $jsonRes['data']->title = "Grocery created.";
            $jsonRes['data']->detail = "Grocery created with no errors, now in your product store.";

            echo json_encode($jsonRes);

        } else {
            
            // create the json response object
            $jsonRes = array();
            $jsonRes['status'] = new stdClass();
            $jsonRes['data'] = new stdClass();

            // status in response
            $jsonRes['status']->success = "false";
            $jsonRes['status']->code = "409";

            $jsonRes['data']->type = "Grocery";
            $jsonRes['data']->title = "Grocery not created.";
            $jsonRes['data']->detail = "Due to an error, the grocery could not be created. Try again.";

            echo json_encode($jsonRes);

        }

    } else {

        $jsonRes = new stdClass();

        $jsonRes->status = "401";
        $jsonRes->title = "Invalid Details";
        $jsonRes->detail = "The username or email and password combination was incorrect.";

        echo json_encode($jsonRes);

    }

} else {

    $jsonRes = new stdClass();

    $jsonRes->status = "401";
    $jsonRes->title = "No Details Supplied";
    $jsonRes->detail = "The username or email and password combination was not provided, therefore, user is not authenticated.";

    echo json_encode($jsonRes);

}