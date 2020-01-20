<?php


class LoginValidation
{
    static private $answer; //answer to captcha question

    //generates and returns question
    //saves answer to field
    public static function generateQuestion()
    {
        $lhOperand = rand(0,10); //randomises left hand operand
        $rhOperand = rand(0,10); //randomises right hand operand
        $operator = rand(0,2); //randomises operator
        switch($operator)
        {
            case 0: self::$answer=($lhOperand+$rhOperand);
                    $question = "$lhOperand+$rhOperand";
                    break;
            case 1: self::$answer=($lhOperand-$rhOperand);
                    $question = "$lhOperand-$rhOperand";
                    break;
            case 2: self::$answer=($lhOperand*$rhOperand);
                    $question = "$lhOperand*$rhOperand";
                    break;
        }
        //var_dump($question);
        var_dump(self::$answer);
        return $question;
    }

    public static function testQuestion()
    {
        $question = self::generateQuestion();
        echo "question: ".$question." answer: ".self::$answer;
    }

    public static function getQuestion()
    {
        return;
    }

    //checks supplied answer to stored answer
    //returns true if answers match, inverse otherwise
    public static function validateAnswer($answer)
    {
        //echo $answer." ".self::$answer;
        var_dump($answer);
        var_dump(self::$answer);
        if($answer == self::$answer)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}