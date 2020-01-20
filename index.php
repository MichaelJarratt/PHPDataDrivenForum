<?php
require_once("Models/PostGetter.php");
require_once("Models/CategoryGetter.php");
session_start(); //apparently I need to do this to be able to access session set in another page
//var_dump($_SESSION);
//initialisation of needed classes
$postGetter = new PostGetter(); //class responsible for fetching posts
$categoryGetter = new CategoryGetter(); //class responsible for fetching categories

$view = new stdClass(); //used to pass information between various pages and classes glued together
//$view->pageTitle = 'Homepage';
/*
if (isset($_GET["categorySelect"]) && !($_GET["categorySelect"]=="all")) //if user has selected a category they want to see
{
    var_dump($_GET);
    $posts = $postGetter->getRecentPosts($_GET["categorySelect"]); //fetches array of posts of $category
}
else
{
    $posts = $postGetter->getRecentPosts(null); //fetches array of posts, no category
}
*/
if(isset($_GET['categorySelect']))
{
    $posts = $postGetter->getRecentPosts($_GET); //fetches array of posts, no category
}
else
{
    $posts = $postGetter->getRecentPosts(null); //fetches array of posts, no category
}
//truncates details that are display
foreach($posts as &$post)
{
    if(strlen($post["title"])>80)
    {
        $post["title"] = substr($post["title"],0,77)."..."; //if title is longer than 80 characters display 77 characets and "..."
    }
    if(strlen($post["content"])>200)
    {
        $post["content"] = substr($post["content"],0,197)."..."; //if content is longer than 200 characters display 197 characets and "..."
    }
}
//var_dump($posts);
$view->posts = $posts; //puts array of posts into view

$categories = $categoryGetter->getAllCatNames(); //retrieves names of categories to display
//var_dump($categories);
$view->categories = $categories; //places categories in view
//echo "checkpoint2";
//var_dump($posts);
require_once('Views/index.phtml');

