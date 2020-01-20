<?php


class Comment
{
    private $hasChildren;
    private $content;
    private $children;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function hasChildren()
    {
        return $this->children;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function content()
    {
        return $this->content;
    }

    public function setChildren($children)
    {
        $this->children = $children;
    }

    public function setHasChildren($hasChildren)
    {
        $this->hasChildren = $hasChildren;
    }

}