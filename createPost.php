<?php
session_start();
//initialisation of shit needed
require_once("Models/CategoryGetter.php");
$categoryGetter = new CategoryGetter();
require_once("Models/PostMaker.php");

//gets categories and put them in view
$view = new stdClass();
$view->categories = $categoryGetter->getAllCatNames();

if(isset($_POST['submit']))
{
    $postMaker = new PostMaker(); //initialisation of needed class
    //sanitising content
    $_POST['title'] = htmlentities($_POST['title']);
    //var_dump($_POST['title']);
    $_POST['content'] = htmlentities($_POST['content']);

    $_POST['titleCheck'] = $postMaker->checkTitle($_POST['title']);
    $_POST['contentCheck'] = $postMaker->checkContent($_POST['content']);
    //if(isset($_FILES['image']['error'])) //if an image is uploaded
    if($_FILES['image']['error']!=4)
    {
        $_POST['imageCheck'] = $postMaker->checkImage($_FILES['image']);
        //var_dump($_POST['imageCheck']);
    }
    else //if not
    {
        $_POST['imageCheck'] = true;
    }
    //var_dump($_FILES['image']);


    if($_POST['imageCheck'] == true) //if file is an image
    {
        $_POST['sizeCheck'] = $postMaker->checkImageSize($_FILES['image']);
    }
    else
    {
        $_POST['sizeCheck'] = false;
    }

    if($_POST['imageCheck'] == true && $_POST['sizeCheck'] == true)
    {
        move_uploaded_file($_FILES['image']['tmp_name'],"images/".basename($_FILES['image']['name']));
    }
    //if all checks have passed
    if($_POST['titleCheck'] == true && $_POST['contentCheck'] == true && $_POST['imageCheck'] == true && $_POST['sizeCheck'] == true)//if both checks passed
    {
        if($_POST['imageCheck'] == true && $_POST['sizeCheck'] == true) //if post has an image
        {
            $image = basename($_FILES['image']['name']);
            $postMaker->commitImagePost($_SESSION['userID'], $_POST['category'], $_POST['title'], $_POST['content'],$image);
        }
        else
        {
            $postMaker->commitPost($_SESSION['userID'], $_POST['category'], $_POST['title'], $_POST['content']);
        }
        header("Location: /index.php");
    }
}

if(isset($_SESSION['loggedIn']))
{
    require_once("Views/createPost.phtml");
}
else
{
    echo "ERROR: you shouldn't be here";
}
