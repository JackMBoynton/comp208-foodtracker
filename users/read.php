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

$identifier = "";
$password = "";

// check the post variables
// make sure that all fields are set and non are empty
if (isset($_POST['identifier']) && !empty($_POST['identifier']) && isset($_POST['password']) && !empty($_POST['password'])) {
    $identifier = $_POST["identifier"];
    $password = $_POST["password"];
}

$queryReturn = $user->read($identifier, $password);
$count = count($queryReturn);

if ($count > 0) {

    $users = array();
    $users["body"] = array();
    $users["count"] = $count;

    $userRow = array(
        "UserID" => $queryReturn[0][1],
        "Username" => $queryReturn[0][0]
    );

    array_push($users["body"], $userRow);

    echo json_encode($users);

} else {

    echo json_encode(
        array("body" => array(), "count" => 0)
    );

}
