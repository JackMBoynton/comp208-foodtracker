<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/dbconfig.php';
include_once '../entities/grocery.php';

$db = new dbconfig();
$conn = $db->getConnection();

$grocery = new grocery($conn);

