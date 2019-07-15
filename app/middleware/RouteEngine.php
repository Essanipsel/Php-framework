<?php


class RouteEngine
{
    private $tabParam;
    private $urlParam;
    private $routeJson;
    private $tabRoutes;
    private $tabMiddlewareError;
    private $errorTab= array(
        "route" => "error",
        "title" => "Framework error",
        "controller" => "ErrorController",
        "method" => "show"
    );
    public $execConfig;
    private $requestType = 'GET';

    public function __construct($routeJson){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') $this->requestType = 'POST';
        $this->routeJson = $routeJson;
        $this->urlParam = strstr($_SERVER['REQUEST_URI'], '?', true) ? strstr($_SERVER['REQUEST_URI'], '?', true) : $_SERVER['REQUEST_URI'];
        $this->requireMiddlewares();
        $this->decomposeUrl();
        $this->extractRoutes();
        $configExec = $this->compareRoutes();
        if(@isset($configExec['middleware'])){
            if(!$this->runMiddleware($configExec['middleware'])) {
                header('Location: '.$this->tabMiddlewareError[0]);
                exit();
            }
        }
        $this->setGlobalFromUrl($configExec['route']);
        $this->execConfig = $configExec;
    }
    private function requireMiddlewares(){
        $middlewares = glob("../app/middleware/*.php");
        foreach ($middlewares as $file){
            $file = substr($file, 18);
            if($file != "RouteEngine.php"){
                require($file);
            }
        }
    }
    private function runMiddleware($middleware){
        $isGood = true;
        foreach ($middleware as $indexM => $tabMiddleware){
            $indexM::init();
            foreach ($tabMiddleware as $rules){
                $attr = $rules['attr'];
                if(!$this->compareAttr($indexM::$$attr,$rules['operator'],$rules['value'])) {
                    $isGood = false;
                    $this->tabMiddlewareError[] = $rules['error'];
                }
            }
        }
        return $isGood;
    }
    private function decomposeUrl(){
        $url = $this->urlParam;
        $this->tabParam = explode('/', $url);
        //remove blanks from tab
        $compt = 0;
        foreach($this->tabParam as $value){
            if($value == null) unset($this->tabParam[$compt]);
            $compt ++;
        }
        $this->tabParam = array_merge($this->tabParam);
    }
    private function extractRoutes(){
        $routeJson = $this->routeJson['routes'];
        foreach($routeJson as $route){
            $tabParam = explode('/', $route['route']);
            //remove blanks from tab
            $compt = 0;
            foreach($tabParam as $value){
                if($value == null) unset($tabParam[$compt]);
                $compt ++;
            }
            $tabParam = array_merge($tabParam);
            $this->tabRoutes[] = $tabParam;
        }
    }
    private function compareRoutes(){
        $tabParam = $this->tabParam;
        $tabRoutes = $this->tabRoutes;
        $indexGoodRoute = -1;
        $indexRoute = 0;
        foreach ($tabRoutes as $routes){
            if(@empty($this->routeJson['routes'][$indexRoute]['request'])) $this->routeJson['routes'][$indexRoute]['request'] = 'GET';
            if($this->routeJson['routes'][$indexRoute]['request'] == $this->requestType) {
                if (empty($routes) && empty($tabParam)) $indexGoodRoute = $indexRoute;
                else if (count($routes) == count($tabParam)) {
                    $indexParam = 0;
                    $isGood = true;
                    foreach ($tabParam as $param) {
                        if (@(!strstr($routes[$indexParam], "{") && $param != $routes[$indexParam])) $isGood = false;
                        $indexParam++;
                    }
                    if ($isGood) $indexGoodRoute = $indexRoute;
                }
            }
            $indexRoute ++;
        }
        if($indexGoodRoute != -1){
            return array(
                "route" => $this->routeJson['routes'][$indexGoodRoute]['route'],
                "title" => $this->routeJson['routes'][$indexGoodRoute]['title'],
                "controller" => $this->routeJson['routes'][$indexGoodRoute]['controller'],
                "method" => $this->routeJson['routes'][$indexGoodRoute]['method'],
                "middleware" => @$this->routeJson['routes'][$indexGoodRoute]['middleware']
            );
        } else {
            return $this->errorTab;
        }
    }
    private function setGlobalFromUrl($route){
        $route = explode('/', $route);
        $compt = 0;
        foreach($route as $value){
            if($value == null) unset($route[$compt]);
            $compt ++;
        }
        $route = array_merge($route);
        $indexVar = 0;
        foreach ($route as $param){
            if(strstr($param, "{")){
                $lengthString = strlen($param) - 2;
                $paramName = substr($param, 1, $lengthString);
                $GLOBALS['url']['param'][$paramName] = $this->tabParam[$indexVar];
            }
            $indexVar ++;
        }
    }
    private function compareAttr($attr, $operator, $value){
        switch($operator){
            case '==':
                if($attr == $value) return true;
                return false;
                break;
            case '!=':
                if($attr != $value) return true;
                return false;
                break;
            case '>=':
                if($attr >= $value) return true;
                return false;
                break;
            case '<=':
                if($attr <= $value) return true;
                return false;
                break;
            case '<':
                if($attr < $value) return true;
                return false;
                break;
            case '>':
                if($attr > $value) return true;
                return false;
                break;
            default:
                return false;
        }
    }
}