<?php
class grocery {

    // connection instance
    private $conn;

    // the table name
    private $tbl = "grocery";

    // table columns
    public $GroceryNo;
    public $Barcode;
    public $Name;
    public $ExpiryDate;
    public $UserID;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /* POST http://comp208-foodtracker/groceries/create.php

    $barcode = this is the barcode of the grocery we will be creating
    $name = the name of the grocery we will be creating
    $expiryDate = the expiry of the grocery being created
    $userID = the id of the user the grocery is being created on behalf of

    This route creates a grocery via the iPhone application scanning / form.

    */
    public function create($barcode, $name, $expiryDate, $userID) {

        // Having to use prepare and binding here, so no SQL injection
        $stmt = $this->conn->prepare("INSERT INTO grocery (Barcode, Name, ExpiryDate, UserID) VALUES (:barcode, :name, :expiryDate, :userID)");
        $stmt->bindValue(':barcode', $barcode, PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':expiryDate', $expiryDate, PDO::PARAM_STR);
        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);

        return $stmt->execute();

    }

    /* POST http://comp208-foodtracker/groceries/readUserGroceries.php

    $userID = this is the user's ID for the groceries we have to get

    This route reads all the groceries for a specific user

    */
    public function readAll($userID) {

        // we need to grab the
        $stmt = $this->conn->prepare("SELECT GroceryNo, Name, ExpiryDate FROM grocery WHERE UserID = :uid");

        $stmt->bindValue(':uid', $userID, PDO::PARAM_INT);

        // execute query
        $stmt->execute();

        // array to return
        $groceries = array();

        $groceriesCount = $stmt->rowCount();

        if ($groceriesCount > 0) {
            
            $groceries['groceries'] = array();
            $groceries['count'] = $groceriesCount;

            // while we have rows
            while ($row = $stmt->fetch()) {

                // extract the row
                extract($row);

                $currentGrocery = array(
                    "GroceryNo" => $GroceryNo,
                    "Name" => $Name,
                    "ExpiryDate" => $ExpiryDate
                );

                array_push($groceries['groceries'], $currentGrocery);

            }

        } else {

            $groceries['groceries'] = array();
            $groceries['count'] = 0;

        }

        return $groceries;

    }

    /* POST http://comp208-foodtracker/groceries/update.php

    $groceryNo = this is the groceries ID for updating

    This route reads all the groceries for a specific user

    */
    public function update($groceryNo, $name, $expiryDate) {

        // create statement and hashed password from param
        $stmt = $this->conn->prepare("UPDATE grocery SET Name = :name, ExpiryDate = :exp WHERE GroceryNo = :groceryNo");

        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':exp', $expiryDate, PDO::PARAM_STR);
        $stmt->bindValue(':groceryNo', $groceryNo, PDO::PARAM_INT);

        return $stmt->execute();

    }

    /* POST http://comp208-foodtracker/groceries/update.php

    $groceryNo = this is the groceries ID for deleting

    This route reads all the groceries for a specific user

    */
    public function delete($groceryNo) {

        $stmt = $this->conn->prepare("DELETE FROM grocery WHERE GroceryNo = :groceryNo");

        $stmt->bindValue(':groceryNo', $groceryNo, PDO::PARAM_INT);

        return $stmt->execute();

    }

    /* POST http://comp208-foodtracker/groceries/readFromBarcode.php

    $barcode = this is the barcode to search for

    ** This is an external API call function **

    This route finds a product based on it's barcode and returns it's name.

    */
    public function barcodeSearch($barcode) {

        // create a request

        // first we need to create the link from the barcode to OF api
        $url = "https://world.openfoodfacts.org/api/v0/product/" . $barcode . ".json";

        // set the options
        $options = array(
            'http' => array(
                'method' => 'GET',
                'User-Agent' => "FoodTracker - iOS - Version 0.1",
                'Host' => 'en.openfoodfacts.org'
            )
        );

        // create context
        $context = stream_context_create($options);

        // open the file via HTTP headers above from options
        $page = file_get_contents($url, false, $context);

        // this is an stdClass Object
        $page = json_decode($page);

        $productName = '';

        // from here we have to use arrows to find what we want
        if (isset($page->product->product_name)) {
            $productName = $page->product->product_name;
        } else {
            $productName = 'Product does not exist';
        }

        return $productName;

    }

    /* POST http://comp208-foodtracker/groceries/getExpiringGroceries.php

    $userID = this is the id of the user logged in that we are finding the groceries for

    This route finds products expiring based on the current user's storage.

    */
    public function getExpiringGroceries($userID) {

        // we need to grab the
        $stmt = $this->conn->prepare("SELECT * FROM grocery WHERE ExpiryDate < NOW() + INTERVAL 3 DAY AND UserID = :uid");

        $stmt->bindValue(':uid', $userID, PDO::PARAM_INT);

        // execute query
        $stmt->execute();

        // array to return
        $expiringGroceries = array();

        $groceriesCount = $stmt->rowCount();

        if ($groceriesCount > 0) {
            
            $expiringGroceries['groceries'] = array();
            $expiringGroceries['count'] = $groceriesCount;

            // while we have rows
            while ($row = $stmt->fetch()) {

                // extract the row
                extract($row);

                $currentGrocery = array(
                    "Name" => $Name
                );

                array_push($expiringGroceries['groceries'], $currentGrocery);

            }

        } else {

            $expiringGroceries['groceries'] = array();
            $expiringGroceries['count'] = 0;

        }

        return $expiringGroceries;

    }

    public function getRecipes($ingredientsToInclude) {

        $url = htmlspecialchars_decode("https://api.spoonacular.com/recipes/findByIngredients?ingredients=" . urlencode($ingredientsToInclude) . "&number=20&limitLicense=false&ranking=1&ignorePantry=false&apiKey=714c4a6fea1b4bd5a04ed47eb457f951");

        // open the file via HTTP headers above from options
        $response = file_get_contents($url);

        // this is an stdClass Object
        $jsonResponse = json_decode($response);

        $recipes = array();
        $recipesCount = count($jsonResponse);

        if ($recipesCount > 0) {

            $recipes['recipes'] = array();
            $recipes['count'] = $recipesCount;

            foreach ($jsonResponse as $recipe) {
                // get the name and ID, we need the name for the table cell text, but we need both for crafting an id
                $recipeID = $recipe->id;
                $recipeName = $recipe->title;

                $baseURL = "https://spoonacular.com/recipes/";
                $recipeNameForURL = str_replace(' ', '-', trim($recipeName));
                $recipeIDForURL = '-' . $recipeID;
                $recipeURL = htmlspecialchars_decode($baseURL . urlencode($recipeNameForURL) . urlencode($recipeIDForURL));

                $currentRecipe = array(
                    'Name' => $recipeName,
                    'URL' => $recipeURL
                );

                array_push($recipes['recipes'], $currentRecipe);
            }

        } else {

            $recipes['recipes'] = array();
            $recipes['count'] = 0;

        }

        return $recipes;

    }

}