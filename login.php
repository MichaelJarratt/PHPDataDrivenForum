<?php
//echo $_SERVER['HTTP_HOST']." ".$_SERVER['PHP_SELF'];
require_once("Models/LoginValidator.php");
session_start();
if(isset($_POST['previousPage']))
{
    $_SESSION['previousPage'] = $_POST['previousPage']; //saves previous page in session so it's not forgotten
}

if(isset($_POST['logOut'])) //if user arrived here after logging out
{
    $previousPage = $_SESSION['previousPage']; //retrieves previousPage so it can be saved
    session_destroy(); //destroys session, logging user out
    session_start(); //starts a new empty session
    $_SESSION['previousPage'] = $previousPage;
}

if(isset($_POST['loginSubmit'])) //if the user has submitted their login
{
    //var_dump($_POST['remember']);
    //var_dump($_POST);
    if(LoginValidator::validateAnswer($_POST['captcha'],$_POST['captchaAnswer'])) //passes in question and answer to validate
    {
        $_POST['captchaStatus'] = true;
    }
    else
    {
        $_POST['captchaStatus'] = false;
    }
    $_POST['credentialCheck'] = LoginValidator::checkCredentials($_POST['email'],$_POST['password']);
    //if all inputs for login were valid
    if ($_POST['credentialCheck']==true && $_POST['captchaStatus'] == true)
    {
        //session_start(); //creates session to put user details in
        $userDetails = LoginValidator::getUser($_POST['email'],$_POST['password'])[0]; //gets user tuple from table
        //var_dump($userDetails);
        $_SESSION['loggedIn'] = true;
        $_SESSION['userID'] = $userDetails['userID'];
        $_SESSION['userName'] = $userDetails['userName'];
        $_SESSION['email'] = $userDetails['email'];
        //var_dump($_SESSION);

        if(isset($_POST['remember'])) //only changes remember state with successful login
        {
            if ($_POST['remember'] == true) //sets cookies
            {
                setcookie('email', $_POST['email'], time() + 60 * 60 * 24 * 7); //cookie valid for 1 week
                setcookie('password', $_POST['password'], time() + 60 * 60 * 24 * 7);
                setcookie('remember',true,time() + 60 * 60 * 24 * 7);
            }
        }
        else //unsets cookies
        {
            //echo "unset";
            setcookie('email', "placeholder", 1); //sets expiry date in past
            setcookie('password', "placeholder", 1);
            setcookie('remember',true, 1);
        }
    }
}

$_POST['captcha'] = LoginValidator::generateQuestion(); //gets captcha question to ask user, changes after validation of previous one
require_once("Views/login.phtml");
