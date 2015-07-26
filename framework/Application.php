<?php
namespace Framework;

use Framework\DI\Container;

class Application{

    protected $config;
    static $container;

    function __construct($config){

        self::$container = new Container();
        $this->config = require($config);

        echo 'Application class <br>';
    }

}