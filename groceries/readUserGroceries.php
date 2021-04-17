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

