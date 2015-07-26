<?php
namespace Framework\Request;

class Request{

    public function getUrl(){

        return $_SERVER['REQUEST_URI'];
    }

    public function isPost(){

        if($_SERVER['REQUES_METHOD'] == 'POST'){
            return true;
        }

        return false;
    }

    public function post($var, $type = 'text'){

        switch($type){
            case 'int':
                $var = (integer)$_POST[$var];
                break;
            default:
                $var = htmlspecialchars($_POST[$var]);
                break;
        }
        return $var;
    }

    public function getVars(){

        $vars = array();

        $uri_parsed = parse_url($this->getUrl());

        if(!empty($uri_parsed['query'])){
            parse_str($uri_parsed['query'], $vars);
        }

        return $vars;
    }
}