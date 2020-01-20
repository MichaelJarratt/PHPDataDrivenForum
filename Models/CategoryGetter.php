<?php
require_once("Models/Database.php");

/*
 * this class is to be used when categories names or ID need to be retrieved
 */
class CategoryGetter
{
    private $database;

    function __construct()
    {
        $this->database = Database::getInstance();
    }

    //returns a list of all category names
    public function getAllCatNames()
    {
        return $this->database->retrieve("SELECT catName FROM Categories");
    }
}