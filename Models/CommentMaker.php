<?php
require_once("Models/Database.php");

class CommentMaker
{
    private $database;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    //makes comments that are replies to other comments
    public function commitCommentReply($parentID,$commentorID,$content)
    {
        //inserts comment into Comments (all other valyes default or auto-increment)
        $this->database->update("INSERT INTO Comments (commentor, content) VALUES (\"$commentorID\",\"$content\") ");
        //selects the latest inserted comment (the one above)
        $result = $this->database->retrieve("SELECT commentID FROM Comments ORDER BY commentID DESC LIMIT 1");
        $childID = $result[0]["commentID"]; //gets ID of child post
        $this->database->update("INSERT INTO CommentReplies (parent, child) VALUES (\"$parentID\",\"$childID\")");
    }

    //makes comments that are directly under posts
    public function commitPostComment($parentID,$commentorID,$content)
    {
        //inserts comment into Comments (all other valyes default or auto-increment)
        $this->database->update("INSERT INTO Comments (commentor, content) VALUES (\"$commentorID\",\"$content\") ");
        //selects the latest inserted comment (the one above)
        $result = $this->database->retrieve("SELECT commentID FROM Comments ORDER BY commentID DESC LIMIT 1");
        $childID = $result[0]["commentID"]; //gets ID of child post
        $this->database->update("INSERT INTO PostComments (post, comment) VALUES (\"$parentID\",\"$childID\")");
    }
}