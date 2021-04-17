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

                $result = array(
                    "Result" => "Failed: Password/confirm password not the same"
                );
            
                echo json_encode($result);
                
            }
        }
        
        // status of creating our product
        if ($status) {
            
            $result = array(
                "Result" => "Success: Password updated"
            );
        
            echo json_encode($result);

        } else {
            
            $result = array(
                "Result" => "Failed: Password not updated"
            );
        
            echo json_encode($result);

        }

    } else {

        $result = array(
            "Result" => "Failed: Data not supplied 1"
        );
    
        echo json_encode($result);

    }

} else {

    $result = array(
        "Result" => "Failed: Data not supplied 2"
    );

    echo json_encode($result);

}
