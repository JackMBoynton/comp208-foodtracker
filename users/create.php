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

    $status = FALSE;
    $exists = FALSE;

    // run our insert query
    if (!empty($email) && !empty($username) && !empty($password)) {
        // the statement to see if the user already exists
        $userStmt = $user->read($email, $password);

        // if user count of 1 is returned, set $status to false
        if (count($userStmt) == 1) {

            $status = FALSE;
            $exists = TRUE;

        } else {

            $status = $user->create($email, $username, $password);

        }

    }

    // check user was created successfully
    if ($status) {

        $queryReturn = $user->read($username, $password); // To obtain the UID, there's potentially a better solution?

        $result = array(
            "Result" => "Success: User created",
            "UserID" => strval($queryReturn[0][1]),
            "Username" => $queryReturn[0][0]
        );


    } else if (!$status && $exists) {

        $result = array(
            "Result" => "Failed: User not created. Already Exists!"
        );

    } else if (!$status) {
        
        $result = array(
            "Result" => "Failed: User not created"
        );

    } 

    echo json_encode($result);

} else {

    $result = array(
        "Result" => "Failed: No email, username or password supplied."
    );

    echo json_encode($result);

}