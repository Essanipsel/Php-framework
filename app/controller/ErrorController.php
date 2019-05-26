<?php


class ErrorController
{
    public function show(){
        echo "Error <br>";
        if(@isset($GLOBALS['url']['param']['code'])) echo $GLOBALS['url']['param']['code'];
    }
}