<?php
require_once("Models/UserGetter.php");
//starts session so it can be accessed
session_start();

$userGetter = new UserGetter(); //creates object to get user info


//user can only see other users details if they are logged in
if(isset($_SESSION['loggedIn']) && isset($_GET['userID']))
{
    $view = new stdClass();
    $view->user = $userGetter->getUserDetails($_GET['userID']); //only done is login valid and ID supplied
    require_once("Views/userPage.phtml");
}
else
{
    echo "<h3>you shouldn't be here, go log in!</h3>";
}
