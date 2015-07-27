<?php
namespace Framework;

use Framework\DI\Service;

class Application{

    protected $config;
    static $container;

    function __construct($config){

        self::$container = new Service();
        $this->config = require($config);

        echo 'Application class <br>';
    }

}