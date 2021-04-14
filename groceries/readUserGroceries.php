<?php

header("Content-Type: application/json; charset=UTF-8");

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

        // we either get back a JSON object of groceries, or not
        $groceries = $grocery->readAll($userID);

        echo json_encode($groceries);

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

