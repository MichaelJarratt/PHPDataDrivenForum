<?php
require_once("Models/Database.php");

/*
 * this class is used for any instance where a post needs to be retrieved from DB
 */
class PostGetter
{
    private $database;

    //constructor
    public function __construct()
    {
        $this->database = Database::getInstance(); //gets instance of Database class
    }

    // returns top 100 posts matching filters in $GET
    public function getRecentPosts($GET)
    {
        $query = "SELECT userName, catName, title, content, datePosted, deleted, archived, postID, views, likes, dislikes, userID FROM Posts, Users, Categories
                  WHERE Users.userID=Posts.poster AND Categories.catID = Posts.category";
        if($GET != null) //if not null
        {
            if($GET['titleSearch']!="") //if the user has searched for a title
            {
                $query.=" AND title LIKE \"%".$GET['titleSearch']."%\"";
            }
            if($GET['contentSearch']) //if the user is searching for content
            {
                $query.=" AND content LIKE \"%".$GET['contentSearch']."%\"";
            }
            if($GET['posterSearch']) //if the user is searching for poster
            {
                $query.=" AND userName LIKE \"%".$GET['posterSearch']."%\"";
            }
            if($GET['categorySelect'] && $GET['categorySelect']!="all") //if the user is searching for category
            {
                $query.=" AND Categories.catName = \"".$GET['categorySelect']."\"";
            }
            $query.=" ORDER BY datePosted DESC LIMIT 100;";
        }
        else
        {
            $query.=" ORDER BY datePosted DESC LIMIT 100;";
        }
        //echo $query;
        return $this->database->retrieve($query);
    }

    /*
     * gets an individual post by ID for viewing in it's own page, also increments that posts views
     */
    public function getPost($ID)
    {
        $this->database->update("UPDATE Posts SET views = views+1 WHERE postID =\"$ID\";"); //increments views
        $result = $this->database->retrieve("SELECT userName, catName, title, content, datePosted, deleted, archived, postID, views, likes, dislikes,userID FROM Posts, Users, Categories
                                                    WHERE Users.userID=Posts.poster AND Categories.catID = Posts.category AND postID = \"$ID\";")[0];
        $images = $this->database->retrieve("SELECT imageName FROM PostImages WHERE postID =\"$ID\""); //gets image associated with post
        if(count($images) == 0) //if it has no image
        {
            $result['imageName'] = null;
        }
        else
        {
            $result['imageName'] = $images[0]['imageName'];
        }
        return $result;

    }

}