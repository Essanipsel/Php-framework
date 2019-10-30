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
        if(@empty($config['scope'])) $config['scope'] = "*";
        $scope = $config['scope'];
        $table = $config['table'];
        $request = "SELECT $scope FROM $table ";
        if(@isset($config['naturaljoin'])) $request .= $this->writeNaturalJoin($config['naturaljoin']);
        if(@isset($config['where'])) $request .= $this->writeWhereParam($config['where']);
        if(@isset($config['orderby'])) $request .= $this->writeOrderBy($config['orderby']);
        if(@isset($config['limit'])) $request .= $this->writeLimitParam($config['limit']);

        return $this->requestEngine($request);
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
    public function update($config){
        $table = $config['table'];
        $request = "UPDATE $table ";
        $request .= $this->writeSetValues($config['values']).' ';
        $request .= $this->writeWhereParam($config['where']);

        return $this->requestEngine($request, false);
    }
    public function delete($config){
        $table = $config['table'];
        $request = "DELETE FROM $table ";
        $request .= $this->writeWhereParam($config['where']);

        return $this->requestEngine($request, false);
    }
    private function writeSetValues($valuesTab){
        $request = "SET ";
        $comptValue = 0;
        foreach($valuesTab as $index => $value){
            if($comptValue > 0) $request .= ",";
            $request .= $index."=:".$index;
            $this->bindTab[$index] = $value;
            $comptValue ++;
        }
        return $request;
    }
    private function requestEngine($request, $return = true){
        $req = $this->bdd->prepare($request);
        $req->execute($this->bindTab);

        if($return) return $req->fetchAll(PDO::FETCH_ASSOC);
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
    private function writeOrderBy($orderTab){
        $request = "ORDER BY ";
        $comptParam = 0;
        foreach ($orderTab as $index => $param){
            if($comptParam > 0) $request .= ', ';
            if(@isset($param['ASC'])) $asc = $param['ASC'];
            else $asc = true;
            $request .= $param['column'] . ' ';
            if(!$asc) $request .= "DESC";
            $comptParam ++;
        }
        $request .= ' ';
        return $request;
    }
    private function writeLimitParam($limitTab){
        $request = "LIMIT ".$limitTab[0];
        if(@isset($limitTab[1])) $request .= ", ".$limitTab[1];

        return $request;
    }
    private function writeNaturalJoin($naturalJoinTable){
        $request = "";
        foreach ($naturalJoinTable as $index => $param){
            $request .= "NATURAL JOIN ".$param;
        }
        $request .= ' ';

        return $request;
    }
}