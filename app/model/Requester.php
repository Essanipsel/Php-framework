<?php

require('SPDO.php');

class Requester
{
    private $bdd;
    private $bindTab;

    public function __construct(){
        $this->bdd = SPDO::getInstance()->getPDO();
    }
    //WHERE & LIMIT implemented
    public function find($config){
        $scope = $config['scope'];
        $table = $config['table'];
        $request = "SELECT $scope FROM $table ";
        if(@isset($config['where'])) $request .= $this->writeWhereParam($config['where']);
        if(@isset($config['limit'])) $request .= $this->writeLimitParam($config['limit']);

        return $this->requestEngine($request);
    }
    private function requestEngine($request, $return = true){
        $req = $this->bdd->prepare($request);
        $req->execute($this->bindTab);

        if($return) return $req->fetchAll();
        else return $this->bindTab;

    }
    private function writeWhereParam($whereTab){
        $request = "WHERE ";
        foreach ($whereTab as $index => $param){
            if($index == "operator"){
                $request .= "$param ";
            } else {
                $request .= $index.$param['operator'].":$index ";
                $this->bindTab[$index] = $param['value'];
            }
        }
        return $request;
    }
    private function writeLimitParam($limitTab){
        $request = "LIMIT ".$limitTab[0];
        if(@isset($limitTab[1])) $request .= ", ".$limitTab[1];

        return $request;
    }
    public function create($config){
        $table = $config['table'];
        $request = "INSERT INTO $table (";
        $requestBinds = ') VALUES(';
        $comptValues = 0;
        foreach ($config['values'] as $index => $value){
            if($comptValues != 0) {
                $request .= ',';
                $requestBinds .= ',';
            }
            $request .= $index;
            $requestBinds .= ':'.$index;
            $this->bindTab[$index] = $value;
            $comptValues ++;
        }
        $request .= $requestBinds.')';

        return $this->requestEngine($request, false);
    }
}