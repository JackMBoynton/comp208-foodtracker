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

if (isset($_POST['identifier']) && !empty($_POST['identifier']) && isset($_POST['oldPassword']) && !empty($_POST['oldPassword'])) {

    $return = $user->read($_POST['identifier'], $_POST['oldPassword']);
    $userCount = count($return);
    $status = FALSE;

    // a user was returned
    if ($userCount == 1) {
        $userID = $return[0][1];

        if (isset($_POST['newPassword']) && !empty($_POST['newPassword']) && isset($_POST['confirmPassword']) && !empty($_POST['confirmPassword'])) {

            if ($_POST['newPassword'] == $_POST['confirmPassword']) {

                $status = $user->update($userID, $_POST['newPassword']);

            } else {

                $jsonRes = new stdClass();

                $jsonRes->status = "401";
                $jsonRes->title = "Confirm password was incorrect.";
                $jsonRes->detail = "The passwords were not the same.";

                echo json_encode($jsonRes);
                
            }
        }
        
        // status of creating our product
        if ($status) {
            
            $jsonRes = new stdClass();

            $jsonRes->status = "200";
            $jsonRes->title = "User Updated";
            $jsonRes->detail = "The password was updated successfully.";

            echo json_encode($jsonRes);

        } else {
            
            $jsonRes = new stdClass();

            $jsonRes->status = "401";
            $jsonRes->title = "User not updated";
            $jsonRes->detail = "The password could not be updated successfully.";

            echo json_encode($jsonRes);

        }

    } else {

        $jsonRes = new stdClass();

        $jsonRes->status = "401";
        $jsonRes->title = "Invalid Details";
        $jsonRes->detail = "The username or email and password combination was not supplied, this is needed as well as the new password.";

        echo json_encode($jsonRes);

    }

} else {

    $jsonRes = new stdClass();

    $jsonRes->status = "401";
    $jsonRes->title = "No Details Supplied";
    $jsonRes->detail = "The username or email and password combination was not provided, therefore, user is not authenticated.";

    echo json_encode($jsonRes);

}
