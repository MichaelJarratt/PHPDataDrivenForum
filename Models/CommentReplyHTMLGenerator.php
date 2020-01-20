<?php


class commentReplyHTMLGenerator
{

    public static function generateReplyBox($commentID)
    {
        $html = "";
        $html=$html."<div id=\"rep".$commentID."\" class='collapse'>";
        $html=$html."<form class='form-control replyBox' action=\"".$_SERVER['PHP_SELF']."\" method='post'>";
        $html=$html."<div class='form-group'>";
        $html=$html.    "<label>comment:</label>";
        $html=$html.    "<textarea class=\"form-control\" name='commentContent'> </textarea>";
        $html=$html."</div>";
        $html=$html."<div class='form-group'>";
        $html=$html.    "<input type='submit' name='commentSubmit' value='submit'>";
        $html=$html."</div>";
        $html=$html."<input type='hidden' name='commentType' value='subComment'>";
        $html=$html."<input type='hidden' name='parentID' value=\"$commentID\">";
        $html=$html."</form>";
        $html=$html."</div>";
        return $html;
    }
}