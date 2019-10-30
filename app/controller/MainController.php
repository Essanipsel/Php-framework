<?php

class MainController
{
    private $requester;

    public function __construct(){
        $this->requester = new Requester();
    }

    public function show(){
        echo "Framework home";
    }
    public function showBlog(){
        echo "Framework blog";
    }
}