<?php

namespace Core;

use ReflectionClass;
use ReflectionParameter;

class Container{
    private array $bindings;

    public function __construct(){
        $this->bindings = [];
    }

    public function bind(string $key, callable $func){
        $this->bindings[$key] = $func;
    }

    public function resolve($key){
        if (array_key_exists($key, $this->bindings)){
            return $this->bindings[$key]();
        }

        return $this->build($key);
    }

    public function build(string $className){
        if (!class_exists($className)){
            throw new \Exception("Classname doesnt exits: ".$className);
        }

        $reflection = new ReflectionClass($className);

        $constructor = $reflection->getConstructor();
        if (!$constructor){
            return new $className;
        }

        $constructorParameters = $constructor->getParameters();
        $dependencies = array_map(function(ReflectionParameter $parameter){
            if ($parameter->isDefaultValueAvailable()){
                return $parameter->getDefaultValue();
            }

            $type = $parameter->getType();
            if (!$type || $type->isBuiltin()){
                throw new \Exception("Couldnt inyect parameter: ".$parameter->getName());
            }

            return $this->resolve($type->getName());
        },$constructorParameters);

        return $reflection->newInstanceArgs($dependencies);
    }
}