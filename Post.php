<?php
//importing
require_once ("Models/PostGetter.php");
require_once ("Models/CommentGetter.php");
require_once ("Models/CommentHTMLGenerator.php");
require_once ("Models/CommentMaker.php");
session_start(); //makes session usable

//initialisation
$view = new stdClass(); //used to pass information between various pages and classes glued together
$view->pageTitle = 'Homepage';
$postGetter = new PostGetter();
$commentGetter = new CommentGetter();

//stops page forgetting post ID when page reloaded
if(isset($_GET["postID"]))
{
    $_SESSION["postID"] = $_GET["postID"];
}
if(isset($_SESSION["postID"]))
{
    $_GET["postID"] = $_SESSION["postID"];
}

//user submitting comment
if(isset($_POST["commentSubmit"])) //if user has submitted a comment
{
    $commentMaker = new CommentMaker();
    $parentID = $_POST["parentID"];
    $commentorID = $_SESSION["userID"];
    $content = $_POST["commentContent"];
    if($_POST["commentType"] == "subComment") //comment is a reply to another comment
    {

        $commentMaker->commitCommentReply($parentID,$commentorID,$content);
    }
    elseif($_POST["commentType"] == "topLevel") //comment made directly under post
    {
        $commentMaker->commitPostComment($parentID,$commentorID,$content);
    }
}

//page content
$post = $postGetter->getPost($_GET["postID"]); //gets Array of posts (only has one) and takes the one tuple from index 0
$view->post = $post;
//$view->comments = $commentGetter->getComments($_GET["postID"]);
$comments = $commentGetter->getComments($_GET["postID"]); //gets descendent comments of post from DB
CommentHTMLGenerator::generateCommentHTML($comments); //generates html and stores it in generator
$view->comments = commentHTMLGenerator::getHTML();

//var_dump($view->comments);
//var_dump($view->comments[0]->getChildren()[0]);

//glue
require_once ("Views/post.phtml");
