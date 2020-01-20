<?php
require_once "Models/Database.php";
$db = Database::getInstance();
//var_dump($db);
//echo"checkpoint";
$db->retrieve("SELECT * FROM Users");

