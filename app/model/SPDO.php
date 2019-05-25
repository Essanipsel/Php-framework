<?php


class SPDO
{
    private $PDOInstance = null;
    private static $instance = null;

    private function __construct()
    {
        $this->PDOInstance = new PDO($GLOBALS['database']['engine'].':host='.$GLOBALS['database']['path'].';dbname='.$GLOBALS['database']['name'].';charset=utf8', $GLOBALS['database']['user'], $GLOBALS['database']['password'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new SPDO();
        }
        return self::$instance;
    }
    public function getPDO(){
        return $this->PDOInstance;
    }
}