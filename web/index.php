<?php

require_once(__DIR__.'/../framework/Loader.php');

Loader::addNamespacePath('Blog\\',__DIR__.'/../src/Blog');

$app = new \Framework\Application(__DIR__.'/../app/config/config.php');
$test = new \Blog\Controller\Test();
$req = new \Framework\Request\Request();

$t = $app::$container->get('request');
//var_dump($t);die;
var_dump($_SERVER);
echo $t->getBasePath();
//$app->run();