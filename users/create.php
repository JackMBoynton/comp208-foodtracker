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
if (isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])) {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
}

$status = FALSE;

// run our insert query
if (!empty($email) && !empty($username) && !empty($password)) {
    $status = $user->create($email, $username, $password);
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

    $jsonRes['data']->type = "User";
    $jsonRes['data']->title = "User created.";
    $jsonRes['data']->detail = "User created with no errors, proceed to login.";

    echo json_encode($jsonRes);


} else {
    
    // create the json response object
    $jsonRes = array();
    $jsonRes['status'] = new stdClass();
    $jsonRes['data'] = new stdClass();

    // status in response
    $jsonRes['status']->success = "false";
    $jsonRes['status']->code = "409";

    $jsonRes['data']->type = "User";
    $jsonRes['data']->title = "User not created.";
    $jsonRes['data']->detail = "Due to an error, the user could not be created. Try again.";

    echo json_encode($jsonRes);

}
