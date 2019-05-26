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
        print_r($builder->update(array(
            "table" => "Articles",
            "values" => array(
                "titleArticle" => "Hello world",
                "autorArticle" => "Nicolas"
            ),
            "where" => array(
                "titleArticle" => array(
                    "operator" => "=",
                    "value" => "Lorem Ipsum"
                )
            )
        )));
        /*$builder->addValue('titleArticle', 'Lorem Ipsum 2');
        $builder->addValue('tagArticle', 'News');
        $builder->addValue('contentArticle', 'NeInteger consectetur nisl sed libero vehicula volutpat. Etiam vel dictum velit. Integer tellus odio, interdum sit amet nibh sit amet, convallis auctor mauris. Pellentesque fringilla pharetra hendrerit. Nunc pellentesque, lacus aliquam efficitur ultricies, ligula augue hendrerit neque, in aliquam turpis enim a tortor. Nam volutpat tempus erat, eget ultrices metus interdum in. Mauris felis nisl, fringilla ut dolor at, pellentesque aliquet libero. Pellentesque finibus mi in porttitor rhoncus. Fusce tristique, lorem eu vulputate mollis, nisl ex posuere ipsum, sit amet bibendum sem arcu commodo ex. Sed porttitor eleifend est, et consequat leo tristique quis. Nam urna est, elementum vel dolor non, porttitor commodo magna. Nullam molestie quis nisl eu egestas. Cras enim est, placerat et lacus sit amet, tristique pharetra elit. ws');
        $builder->addValue('autorArticle', 'Alain TERRIEUR');
        $builder->create();*/

    }
    public function showBlog(){
        echo "Framework blog - Article ".$GLOBALS['url']['param']['id'];
    }
}