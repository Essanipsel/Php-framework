<?php

require('Requester.php');

class RequestBuilder
{
    private $naturalJoinTab;
    private $whereTab;
    private $limitTab;
    private $valuesTab;
    private $orderByTab;
    public $scope;
    public $table;
    private $requester;

    public function __construct(){
        $this->requester = new Requester();
    }
    public function cleanBuilder(){
        $this->naturalJoinTab = array();
        $this->whereTab = array();
        $this->limitTab = array();
        $this->valuesTab = array();
        $this->orderByTab = array();
        $this->scope = "";
        $this->table = "";
    }
    public function setTable($table){
        $this->table = $table;
    }
    public function setScope($scope){
        $this->scope = $scope;
    }
    public function update($config = null){
        if($config != null) return $this->requester->update($config);
        else if(isset($this->table) && isset($this->valuesTab) && isset($this->whereTab)) return $this->requester->update($this->buildTabRequest());
        else return false;
    }
    public function delete($config = null){
        if($config != null) return $this->requester->delete($config);
        else if(isset($this->table) && isset($this->whereTab)) return $this->requester->delete($this->buildTabRequest());
        else return false;
    }
    public function addWhere($column, $operator, $value, $linkOperator = null){
        if($linkOperator != null) $this->whereTab["operator"] = $linkOperator;
        $this->whereTab[$column] = array(
            "operator" => $operator,
            "value" => $value
        );
    }
    public function addOrderBy($column, $asc = true){
        $this->orderByTab[] = array(
            "column" => $column,
            "ASC" => $asc
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
    public function addNaturalJoin($table){
        $this->naturalJoinTab[] = $table;
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
            "naturaljoin" => $this->naturalJoinTab,
            "values" => $this->valuesTab,
            "where" => $this->whereTab,
            "orderby" => $this->orderByTab,
            "limit" => $this->limitTab
        );
    }
}