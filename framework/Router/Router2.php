<?php

namespace Core;

class Router
{

    const SEPARATORS = '/';
    const REGEX_DELIMITER = '~';

    private $routes;


    public function __construct($routes){
        $this->routes = $routes;
    }

    public function find2($url){
        foreach($this->routes as $name => $row){
            if(!empty($row['request_method'])){
                if($_SERVER['REQUEST_METHOD'] != $row['request_method']){
                    continue;
                }
            }
            preg_match_all('~\{\w+\}~', $row['pattern'], $matches);
            $reg = $row['pattern'];
            if(!empty($row['requirements'])){
                $vars = array();
                foreach($matches[0] as $match){
                    $key=str_replace(['{','}'], '', $match);
                    if(array_key_exists($key, $row['requirements'])){
                        $vars[$match] = '('.$row['requirements'][$key].')';
                    }else{
                        $vars[$match] = '(\w+)';
                    }
                }
                foreach($vars as $pattern => $replacement){
                    $reg = preg_replace('~'.preg_quote($pattern, '/').'~', $replacement, $reg);
                }
            }else{
                foreach($matches[0] as $pattern){
                    $reg = preg_replace('~'.preg_quote($pattern, '/').'~','(\w+)',$reg);
                }
            }
            if(preg_match('~'.$reg.'~', $url, $values)) {
                array_shift($values);
                $params = array();
                $i = 0;
                foreach($matches[0] as $key){
                    $params[str_replace(['{','}'],'',$key)] = htmlspecialchars($values[$i]);
                    $i++;
                }
                $result = array(
                    'name'=> $name,
                    'controller' => $row['controller'],
                    'action' => $row['action'],
                    'params' =>$params,
                );
                var_dump($result);die;
                return $result;
            }
        }
        throw new \Exception();
    }



    public function find($uri){

        $res = array();

        foreach ($this->routes as $item => $row) {

            $tokens= array();
            $variables = array();
            $pattern = $row['pattern'];

            preg_match_all('~\{\w+\}~', $pattern, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

            $pos = 0;
            foreach ($matches as $match) {
                $varName = substr($match[0][0], 1, -1);
                // get all static text preceding the current variable
                $precedingText = substr($pattern, $pos, $match[0][1] - $pos);

                $pos = $match[0][1] + strlen($match[0][0]);

                $precedingChar = strlen($precedingText) > 0 ? substr($precedingText, -1) : '';

                $isSeparator = '' !== $precedingChar && false !== strpos(self::SEPARATORS, $precedingChar);

                if ($isSeparator && strlen($precedingText) > 1) {
                    $tokens[] = array('text', substr($precedingText, 0, -1));
                } elseif (!$isSeparator && strlen($precedingText) > 0) {
                    $tokens[] = array('text', $precedingText);
                }

                if(!empty($row['requirements'][$varName]))
                    $regexp = $row['requirements'][$varName];
                else
                    $regexp = null;

                if (null === $regexp) {
                    $regexp = '[\w]+';
                }

                $tokens[] = array('variable', $isSeparator ? $precedingChar : '', $regexp);

                $variables[] = $varName;

            }

            if ($pos < strlen($pattern)) {
                $tokens[] = array('text', substr($pattern, $pos));
            }

            $regexp = '';

            for ($i = 0, $nbToken = count($tokens); $i < $nbToken; $i++) {
                $regexp .= $this->computeRegexp($tokens, $i);
            }

            $reg = self::REGEX_DELIMITER.'^'.$regexp.'$'.self::REGEX_DELIMITER.'s';

            if(preg_match($reg, $uri, $match)){
                array_shift($match);
                $res['params'] = array_combine($variables, $match);
                $res['controller'] = $row['controller'];
                $res['action'] = $row['action'];

//                $res['pattern'] = $row['pattern'];
//                $res['regexp'] = $reg;
//                $res['uri'] = $uri;

                return $res;
            }
        }
        return false;
    }

    function computeRegexp(array $tokens, $index){
        $token = $tokens[$index];
        if ('text' === $token[0]) {
            // Text tokens
            return preg_quote($token[1], self::REGEX_DELIMITER);
        } else {
            $regexp = sprintf('%s(%s)', preg_quote($token[1], self::REGEX_DELIMITER), $token[2]);
            return $regexp;
        }
    }



}