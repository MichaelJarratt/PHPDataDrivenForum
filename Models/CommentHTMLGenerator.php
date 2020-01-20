<?php
require_once("Models/CommentReplyHTMLGenerator.php");
//session_start(); //starts session so it can be accessed in here
class CommentHTMLGenerator
{
    private static $html ="";

    public static function generateCommentHTML(&$comments)
    {
        //session_start();
        foreach($comments as $comment)
        {
            self::$html=self::$html."<div class=\"container-fluid\">";
            //var_dump($comment);
            self::$html=self::$html."<a href=\"#comm".$comment->content()['commentID']."\" data-toggle=\"collapse\">".$comment->content()['userName'].":</a>"; //header
            self::$html=self::$html."<div id=\"comm".$comment->content()['commentID']."\" class='collapse' style='border-left: solid;' >"; //header
            self::$html=self::$html."<div class=\"comment\">"; //wrapper to separate content boxes
            self::$html=self::$html."<p><a href=\"userPage.php?userID=".$comment->content()['commentor']."\">".$comment->content()["userName"].":</a></p>";
            self::$html=self::$html."<p>".$comment->content()["content"]."</p>";
            self::$html=self::$html."<div class='commentinfo'><p>likes ".$comment->content()["likes"]."</p> <p>dislikes ".$comment->content()["dislikes"]."</p>";
            if(isset($_SESSION["loggedIn"])) //if the user is logged in display the reply button
            {
                self::$html=self::$html."<p>\"<a href=\"#rep".$comment->content()['commentID']."\" data-toggle=\"collapse\">reply</a></p>";
            }
            self::$html=self::$html."</div>"; //closes commentinfo
            self::$html=self::$html."</div>"; //closes comment

            if(isset($_SESSION["loggedIn"])) //if user is logged in generate comment reply form
            {
                self::$html = self::$html . "<div class='commentReply'>";
                self::$html = self::$html . CommentReplyHTMLGenerator::generateReplyBox($comment->content()['commentID']);
                self::$html = self::$html . "</div>";
            }

            if ($comment->hasChildren())
            {
                //var_dump($comment->getChildren());
                $children = $comment->getChildren();
                self::generateCommentHTML($children);
            }
            self::$html=self::$html."</div>"; //footer
            self::$html=self::$html."</div>";
        }
        return;
    }

    //returns generated html and clears field for future use
    public static function getHTML()
    {
        $temp = self::$html;
        //var_dump(self::$html);
        self::$html =""; //clears static field
        return $temp;
    }
}