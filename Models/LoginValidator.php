<?php
require_once("Models/Database.php");

class LoginValidator
{
    //generates a question and returns answer
    public static function generateQuestion()
    {
        $lhOperand = rand(0,10); //randomises left hand operand
        $rhOperand = rand(0,10); //randomises right hand operand
        $operator = rand(0,2); //randomises operator
        switch($operator)
        {
            case 0:
                $question = "$lhOperand+$rhOperand";
                break;
            case 1:
                $question = "$lhOperand-$rhOperand";
                break;
            case 2:
                $question = "$lhOperand*$rhOperand";
                break;
        }
        //var_dump($question);
        return $question;
    }

    //takes a question and an answer, calculates answer and compares it to answer provided
    public static function validateAnswer($question, $answer)
    {
        $correctAnswer = self::calculate($question); //calculates answer to question
        if($answer==$correctAnswer)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //calculates and returns answer to $question
    private static function calculate($question)
    {
        $operands = preg_split("/[-+*]/",$question);
        //var_dump($operands);
        $operator = preg_split("/[0-9]+/",$question);
        //var_dump($operator);
        if($operator[1]=="+")
            $answer = $operands[0] + $operands[1];
        if($operator[1]=="-")
            $answer = $operands[0] - $operands[1];
        if($operator[1]=="*")
            $answer = $operands[0] * $operands[1];

        //var_dump($answer); var_dump($question);
        return $answer;
    }

    //checks entered details against database entry and confirms if account details are correct
    public static function checkCredentials($email,$password)
    {
        $pwHash = hash("md5",$password); //hashes password
        $database = Database::getInstance();
        $account = $database->retrieve("SELECT * FROM Users WHERE email=\"$email\" AND pwHash=\"$pwHash\"");
        if(count($account)==1) //if an account exists with that email and password hash
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //returns tuple (minus pwhash and dateJoined) from Users table
    public static function getUser($email,$password)
    {
        $pwHash = hash("md5",$password); //hashes password
        $database = Database::getInstance();
        $result = $database->retrieve("SELECT userID, userName, email FROM Users WHERE email=\"$email\" AND pwHash=\"$pwHash\"");
        return $result;
    }
}