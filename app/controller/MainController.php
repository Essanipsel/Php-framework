<?php

require('../app/model/RequestBuilder.php');

class MainController
{
    private $requester;

    public function __construct(){
        $this->requester = new Requester();
    }

    public function show(){
        $builder = new RequestBuilder();


    }
    public function showBlog(){
        echo "Framework blog - Article ".$GLOBALS['url']['param']['id'];
    }
}