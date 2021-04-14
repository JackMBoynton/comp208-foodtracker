<?php
class user {

    // connection instance
    private $conn;

    // table name
    private $tbl = "user";

    // table columns
    public $Email;
    public $Username;
    public $Password;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /* POST http://comp208-foodtracker/users/create.php

    $email = the email for the user signing up
    $username = the username for the user signing up
    $password = Password for the user signing up, to be hashed before inserting

    This route creates a User via a post request using the sign up form from the iPhone app.

    */
    public function create($email, $username, $password) {

        // create statement and hashed password from param
        $stmt = $this->conn->prepare("INSERT INTO user (Email, Username, Password) VALUES (:email, :username, :password)");
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

        // bind variables
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':password', $hashedPwd, PDO::PARAM_STR);

        return $stmt->execute();

    }

    /* POST http://comp208-foodtracker/users/read.php

    $identifier = the username or email to be checked when logging in
    $password = Password for the user logging in, to be hashed and compared in db

    This route tries to match the entered details with a User in the database from a form on the iPhone app.

    */
    public function read($userIdentifier, $userPassword) {

        // find the identifier, then pull the password, then match them, then if they match execute a full sql

        // email and a username statement as we allow the user to log in with either
        $emailStmt = $this->conn->prepare("SELECT * FROM user WHERE Email = :email");
        $usernameStmt = $this->conn->prepare("SELECT * FROM user WHERE Username = :username");

        // execute the statement which brings us back a user or no user
        if (filter_var($userIdentifier, FILTER_VALIDATE_EMAIL)) {
            $emailStmt->bindValue(':email', $userIdentifier);
            $emailStmt->execute();
        } else {
            $usernameStmt->bindValue(':username', $userIdentifier);
            $usernameStmt->execute();
        }

        if (filter_var($userIdentifier, FILTER_VALIDATE_EMAIL)) {
            $count = $emailStmt->rowCount();
            $row = $emailStmt->fetch();
        } else {
            $count = $usernameStmt->rowCount();
            $row = $usernameStmt->fetch();
        }

        // if we actually have a user
        if ($count > 0) {

            // extract it
            extract($row);

            $toReturn = array();

            // check the passwords match - userPassword vs $Password which should come from the extract($row) statement.
            if (password_verify($userPassword, $Password)) {
                $forReturn = array($Username, $UserID);
                array_push($toReturn, $forReturn);
            }

        } else {

            // give an empty array, as we have no user
            $toReturn = array();

        }

        // return the array with or without a user
        return $toReturn;

    }

    /* POST http://comp208-foodtracker/users/update.php

    $id = the id of the currently logged in user, to be used in the WHERE UPDATE clause
    $newPassword = the new password the user enters in the iPhone form
    $confirmedPassword = the confirmed password to make sure $newPassword and $confirmedPassword match - this does not matter for our query and will not be included

    This route updates the password of the currently logged in user via their user id, comparing both passwords, then if they match, UPDATE query will update
    Password field on user id.

    */
    public function update($id, $newPassword) {

        // create statement and hashed password from param
        $stmt = $this->conn->prepare("UPDATE user SET Password = :pwd WHERE UserID = :uid");
        $hashedPwd = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt->bindValue(':pwd', $hashedPwd, PDO::PARAM_STR);
        $stmt->bindValue(':uid', $id, PDO::PARAM_INT);

        return $stmt->execute();

    }

    /* POST http://comp208-foodtracker/users/delete.php

    $id = the id of the currently logged in user, to be used in the DELETE query

    This route deletes the currently logged in user where UserID = $id

    */
    public function delete($id) {

        $stmt = $this->conn->prepare("DELETE FROM user WHERE UserID = :uid");

        $stmt->bindValue(':uid', $id, PDO::PARAM_INT);

        return $stmt->execute();

    }

}