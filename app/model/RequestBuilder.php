<?php

require('Requester.php');

class RequestBuilder
{
    private $whereTab;
    private $limitTab;
    public $scope;
    public $table;
    private $requester;

    public function __construct(){
        $this->requester = new Requester()
;    }

    public function addWhere($column, $operator, $value, $linkOperator = null){
        if($linkOperator != null) $this->whereTab["operator"] = $linkOperator;
        $this->whereTab[$column] = array(
            "operator" => $operator,
            "value" => $value
        );
    }
    public function addLimit($start, $length = null){
        $this->limitTab[0] = $start;
        if($length != null) $this->limitTab[1] = $length;
    }
    public function find(){
        if(isset($this->scope) && isset($this->table)){
            $result = $this->requester->find($this->buildTabRequest());
            return $result;
        }
        else return false;
    }
    private function buildTabRequest(){
        return array(
            "scope" => $this->scope,
            "table" => $this->table,
            "where" => $this->whereTab,
            "limit" => $this->limitTab
        );
    }
}