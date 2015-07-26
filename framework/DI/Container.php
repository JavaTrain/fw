<?php

namespace Framework\DI;

class Container extends \ArrayObject
{

    public function get($key)
    {

        if (is_callable($this[$key])) {
            return call_user_func($this[$key]);
        }

        $classMap = require(__DIR__ . '/classes.php');

        if(array_key_exists($key, $classMap)){

            $this[$key] = function() use ($classMap, $key) {
                return new $classMap[$key];
            };

            return $this->get($key);
        }

        echo "No found";
        //throw new \Exception("Can not find service definition under the key [ $key ]");
    }


}