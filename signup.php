<?php
//echo $_SERVER['HTTP_HOST']." ".$_SERVER['PHP_SELF'];
require_once ("Models/SignUp.php");

if(isset($_POST['submit'])) //if the user has submitted their login
{
    $signUp = new SignUp();
    //var_dump($_POST);

    //sanitises inputs
    $_POST['userName'] = htmlentities($_POST['userName']);
    $_POST['email'] = htmlentities($_POST['email']);

    //gives fields to SignUp object to validate
    $_POST['userNameLengthCheck'] = $signUp->checkUserNameLength($_POST['userName']); //checks the userName length does not exceed character limit
    $_POST['userNameCheck'] = $signUp->checkUserName($_POST['userName']); //finds if username has been taken already
    $_POST['emailCheck'] = $signUp->checkEmail($_POST['email']);//finds ifemail has already been taken

    //checks if entered passwords match
    if(!($_POST['password'] == $_POST["passwordConfirmation"]))
    {
        $_POST['passwordCheck'] = false;
    }
    else
    {
        $_POST['passwordCheck'] = true;
    }

    //if all checks passed
    if($_POST['passwordCheck'] == true && $_POST['emailCheck'] == true && $_POST['userNameCheck'] == true && $_POST['userNameLengthCheck'] == true)
    {
        $_POST['valid'] = true;
        $signUp->commitToDB($_POST['userName'],$_POST['password'],$_POST['email']);
    }
    else
    {
        $_POST['valid'] = false;
    }
    //var_dump($_POST['valid']);
}

require_once("Views/signup.phtml");