<?php
require_once("Models/Database.php");

class PostMaker
{
    private $database;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    //checks if title is within allowed character limit
    public function checkTitle($title)
    {
        if(strlen($title)>300 || strlen($title)=="") //if title is longer than max characters or is empty
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    //checks if content is within allowed character limit
    public function checkContent($content)
    {
        if(strlen($content)>2000 || strlen($content)=="") //if content is longer than max characters or is empty
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    //checks if file supplied is an image
    public function checkImage($file)
    {
        //var_dump($file);
        $check = getimagesize($file['tmp_name']); //returns false if file is not an image
        if($check==false) //if file is not an image
        {
            return false;
        }
        else
        {
            return true;
        }
        //var_dump($check);
    }

    //checks size of image, returns false if over 512KB
    public function checkImageSize($image)
    {
        $fileSize = $image['size'];
        if($fileSize<=524288) //if file isn't over 512KB
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //for committing posts with images
    public function commitImagePost($userID,$category,$title,$content,$imageName)
    {
        $this->commitPost($userID,$category,$title,$content); //commits normal fields with commitPost;
        $postID = $this->database->retrieve("SELECT postID FROM Posts ORDER BY postID DESC LIMIT 1")[0]['postID'];
        $this->database->update("INSERT INTO PostImages(postID, imageName) 
                                        VALUES (\"$postID\",\"$imageName\")");
    }

    //commits post to database
    public function commitPost($userID,$category,$title,$content)
    {
        //$date = date("y-m-d"); //gets current date
        $catID = $this->database->retrieve("SELECT catID FROM Categories WHERE catName=\"$category\"")[0]['catID']; //gets ID of category from DB
        $this->database->update("INSERT INTO Posts(poster, category, title, content, datePosted) 
                                        VALUES (\"$userID\",\"$catID\",\"$title\",\"$content\",NOW())");
    }
}
