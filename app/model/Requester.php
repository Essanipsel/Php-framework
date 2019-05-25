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
    private function requestEngine($request){
        $req = $this->bdd->prepare($request);
        $req->execute($this->bindTab);

        return $req->fetchAll();
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
}