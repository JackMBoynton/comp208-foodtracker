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
    
    $result = array(
        "Result" => "Success",
        "UserID" => strval($queryReturn[0][1]), // Need the ID in String as well
        "Username" => $queryReturn[0][0]
    );

    echo json_encode($result);

} else {

    $result = array(
        "Result" => "Failed: Authentication incorrect username/password"
    );

    echo json_encode($result);

}
