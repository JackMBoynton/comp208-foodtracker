<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/dbconfig.php';
include_once '../entities/user.php';
include_once '../entities/grocery.php';

$db = new dbconfig();
$conn = $db->getConnection();

$user = new user($conn);
$grocery = new grocery($conn);


// call read function from user, POSTing with uname and pwd if they're set
if (isset($_POST['identifier']) && !empty($_POST['identifier']) && isset($_POST['password']) && !empty($_POST['password'])) {

    $return = $user->read($_POST['identifier'], $_POST['password']);
    $userCount = count($return);

    // a user was returned
    if ($userCount == 1) {
        $userID = $return[0][1];

        if (isset($_POST['groceryNo']) && !empty($_POST['groceryNo']) && isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['expiry']) && !empty($_POST['expiry'])) {
			$status = $grocery->update($_POST['groceryNo'], $_POST['name'], $_POST['expiry']);
		}

		if ($status) {

			$result = array(
                "Result" => "Success: Grocery updated"
            );
        
            echo json_encode($result);

		} else {

			$result = array(
                "Result" => "Failed: API error"
            );
        
            echo json_encode($result);

		}

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
