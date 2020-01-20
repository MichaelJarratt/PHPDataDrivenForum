<?php
require_once("Models/Database.php");
require_once("Models/Comment.php");

/*
 * this class is for getting comments from the database
 */
class CommentGetter
{
    private $database;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    //returns structured lis of all comments under $ID
    public function getComments($PostID)
    {
        $rawComments = $this->getTopLevelComments($PostID);
        $comments = [];
        foreach ($rawComments as $content)
        {
            $comment = new Comment($content);
            array_push($comments,$comment);
        }
        //var_dump($comments);
        $this->getCommentReplies($comments);
        //var_dump($comments);
        //var_dump($comments[1]);
        //var_dump($comments[1]->getChildren());
        return $comments;
    }

    //returns comments made on post ID
    private function getTopLevelComments($ID)
    {
        return $this->database->retrieve("SELECT commentID, commentor, content, likes, dislikes, archived, deleted, userName FROM Comments,Users WHERE userID = commentor AND commentID IN (SELECT comment FROM PostComments WHERE post=\"$ID\")");
    }

    //recursive function, gets all comments which are descendents of comments passed in
    private function getCommentReplies(&$comments)
    {
        foreach($comments as $comment)
        {
            $children = $this->getChildrenOf($comment->content()["commentID"]); //gets array of children of comment
            if(count($children)!=0) //if comment has children
            {
                $replies = []; //empty array to hold comment children - replies
                foreach($children as $replyContent) //for every child of comment
                {
                    $childComment = new Comment($replyContent); //makes comment with content of reply
                    array_push($replies, $childComment); //add comment to list of children
                }
                $comment->setHasChildren(true); //sets has children for parent comment to true
                $comment->setChildren($replies); //sets parent comments children as pointer to array of children
                $this->getCommentReplies($replies);
            }
            else
            {
                $comment->setHasChildren(false); //parent comment is set to not be a parent
            }
        }
        return;
    }

    //gets children of $ID
    public function getChildrenOf($ID)
    {
        $children = $this->database->retrieve("SELECT commentID, commentor, content, likes, dislikes, archived, deleted, userName FROM Comments, Users WHERE userID = commentor AND commentID IN (SELECT child FROM CommentReplies WHERE parent = \"$ID\");");
        //var_dump($children);
        return $children;
    }
}