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
        $expiringGroceriesResp = $grocery->getExpiringGroceries($userID);
		$expiringGroceriesArr = $expiringGroceriesResp['groceries'];

		$stringOfIngredients = '';

		foreach ($expiringGroceriesArr as $expiringGrocery) {
			$stringOfIngredients .= ' ' . $expiringGrocery['Name'];
		}

		// $stringOfIngredients now has all our expiring ingredients as a string with spaces seperating, so we need to modify this

		$stringOfIngredients = trim($stringOfIngredients);
		$stringOfIngredients = str_replace(' ', ', ', $stringOfIngredients);

		// $stringOfIngredients is now a string of expiring groceries, split by comma which we needed
		$recipes = $grocery->getRecipes($stringOfIngredients);

		echo json_encode($recipes);


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