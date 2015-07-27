<?php
/**
 * File Description
 *
 * PHP version 5
 *
 * @category   Framework
 * @package    Framework
 * @subpackage Request
 * @author     Serhii Tsybulniak
 * @copyright  2011-2015 mindk (http://mindk.com). All rights reserved.
 * @license    http://mindk.com Commercial
 * @link       http://mindk.com
 */

/**
 * Class Description
 *
 * @category   Framework
 * @package    Framework
 * @subpackage Request
 * @author     Serhii Tsybulniak
 * @copyright  2011-2015 mindk (http://mindk.com). All rights reserved.
 * @license    http://mindk.com
 * @link       http://mindk.com
 */

namespace Framework\Request;

class Request{

    /**
     * Return base path of the requested URL
     *
     * @return  string
     */
    public function getBasePath(){

        $url = parse_url($this->getUrl());
        return $url['path'];
    }

    /**
     * Return requested URL
     *
     * @return  string
     */
    public function getUrl(){

        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Check if requested method is "POST"
     *
     * @return  bool
     */
    public function isPost(){

        if($_SERVER['REQUES_METHOD'] == 'POST'){
            return true;
        }

        return false;
    }

    /**
     * Method for safe use of variables from the global array $_POST.
     *
     * @param   $var                The requested variable
     * @param   $relative_class     The requested type of variable parameter
     *
     * @return string|integer
     *
     */
    public function post($var, $type = 'text'){

        switch($type){
            case 'int':
                $var = (integer)$_POST[$var];
                break;
            case 'text':
                $var = htmlspecialchars($_POST[$var]);
                break;
            default:
                $var = $_POST[$var];
                break;
        }
        return $var;
    }

    /**
     * Method for safe use of variables from the global array $_GET.
     *
     * @param   $var                The requested variable
     * @param   $relative_class     The requested type of variable parameter
     *
     * @return string|integer
     *
     */
    public function query($var, $type = 'text'){

        switch($type){
            case 'int':
                $var = (integer)$_GET[$var];
                break;
            case 'text':
                $var = htmlspecialchars($_GET[$var]);
                break;
            default:
                $var = $_GET[$var];
                break;
        }
        return $var;
    }
}