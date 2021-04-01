<?php

class dbconfig {

    // db info
    // port 3307 is for MariaDB, MySQL is 3306 but normal by default
    private $db_host = "localhost:3307";
    private $db_username = "root";
    private $db_password = "";
    private $db_database = "foodtracker";
    private $db_charset = "utf8";

    public $connection;

    // connect to the db
    public function getConnection() {

        $dsn = "mysql:host=$this->db_host;dbname=$this->db_database;charset=$this->db_charset";
        $opt = array(
            PDO::ATTR_ERRMODE         => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        );

        $this->connection = null;

        try {
            $this->connection = new PDO($dsn, $this->db_username, $this->db_password, $opt);
            $this->connection->exec("SET NAMES utf8");
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }

        return $this->connection;

    }

}