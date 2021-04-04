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
    echo '{';
    echo '"message:": User was created."';
    echo '}';
} else {
    echo '{';
    echo '"message:": Unable to create User."';
    echo '}';
}
