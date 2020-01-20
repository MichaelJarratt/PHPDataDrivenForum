<?php
require_once("Models/Database.php");

class userGetter
{
    private $database;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    //retrieves tuple of user that has supplied ID
    public function getUserDetails($userID)
    {
        $result = $this->database->retrieve("SELECT userName,dateJoined,email FROM Users WHERE userID=\"$userID\"");
        return $result[0]; //takes user tuple from result and returns it
    }
}