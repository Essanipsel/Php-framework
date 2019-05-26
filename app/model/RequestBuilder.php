<?php

require('Requester.php');

class RequestBuilder
{
    private $whereTab;
    private $limitTab;
    private $valuesTab;
    public $scope;
    public $table;
    private $requester;

    public function __construct(){
        $this->requester = new Requester();
    }
    public function update($config = null){
        if($config != null) return $this->requester->update($config);
    }
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
    public function addValue($index, $value){
        $this->valuesTab[$index] = $value;
    }
    public function addValues($valueTab){
        foreach($valueTab as $index => $value){
            $this->addValue($index, $value);
        }
    }
    public function create($config = null){
        if($config != null){
            return $this->requester->create($config);
        } else if (isset($this->table) && isset($this->valuesTab)){
            return $this->requester->create($this->buildTabRequest());
        }
        else return false;
    }
    public function find($config = null){
        if($config != null){
            return $this->requester->find($config);
        }
        else if(isset($this->table)){
            return $this->requester->find($this->buildTabRequest());
        }
        else return false;
    }
    public function findOne($config = null){
        if($config != null) return $this->find($config)[0];
        return $this->find()[0];
    }
    private function buildTabRequest(){
        return array(
            "scope" => $this->scope,
            "table" => $this->table,
            "values" => $this->valuesTab,
            "where" => $this->whereTab,
            "limit" => $this->limitTab
        );
    }
}