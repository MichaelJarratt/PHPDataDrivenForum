<?php
require_once("Models/Database.php");

class SignUp
{
    private $database;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    //checks if the username is already present in the DB
    public function checkUserName($userName)
    {
        $results = $this->database->retrieve("SELECT userName FROM Users WHERE userName = \"$userName\"");
        //var_dump($results);
        if(count($results) == 0)
        {
            return true; //if there are no records with the same username
        }
        else
        {
            return false; //if this is a duplicate username
        }
    }

    //checks if the email is already present in the DB
    public function checkEmail($email)
    {
        $results = $this->database->retrieve("SELECT email FROM Users WHERE email = \"$email\"");
        //var_dump($results);
        if(count($results) == 0)
        {
            return true; //if there are no records with the same username
        }
        else
        {
            return false; //if this is a duplicate username
        }
    }

    //checks that username does not exceed field character limit
    public function checkUserNameLength($userName)
    {
        if(strlen($userName)>30) //30 = max username length
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    //updates DB with user information
    public function commitToDB($userName,$password,$email)
    {
        $passwordHash = hash("md5",$password);
        //echo $passwordHash;
        //$date = date("y-m-d"); //gets todays date //replaced by NOW() -mysql function
        //echo $date;
        //inserts userName,pwHash,dateJoined and email into Users
        $this->database->update("INSERT INTO Users (userName, pwHash, dateJoined, email) VALUES (\"$userName\",\"$passwordHash\",NOW(),\"$email\");");
    }
}