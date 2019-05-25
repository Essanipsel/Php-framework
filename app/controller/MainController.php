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
        $builder->scope = "*";
        $builder->table = "Articles";
        $builder->addWhere('titleArticle', '=', 'Lorem Ipsum');
        $builder->find();
        /*$requestParam = array(
            "scope" => "*",
            "table" => "Articles",
            "where" => array(
                "titleArticle" => array(
                    "operator" => "=",
                    "value" => "Lorem Ipsum"
                ),
                "operator" => "AND",
                "tagArticle" => array(
                    "operator" => "=",
                    "value" => "News"
                )
            ),
            "limit" => array(
                0 => "0",
                1 => "10"
            )
        );
        print_r($this->requester->find($requestParam));*/
    }
    public function showBlog(){
        echo "Framework blog - Article ".$GLOBALS['url']['param']['id'];
    }
}