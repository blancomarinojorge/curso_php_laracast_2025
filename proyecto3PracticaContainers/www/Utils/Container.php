<?php

namespace Utils;

use ReflectionClass;
use ReflectionParameter;

class Container
{
    private array $bindings;

    /**
     * @param array $bindings
     */
    public function __construct(array $bindings = [])
    {
        $this->bindings = $bindings;
    }

    public function bind(string $class, callable $fn){
        $this->bindings[$class] = $fn;
    }

    public function resolve($class){
        if (array_key_exists($class, $this->bindings)){
            return $this->bindings[$class]();
        }

        return $this->build($class);
    }

    public function build(string $class){
        if (!class_exists($class)){
            throw new \Exception("Class {$class} not found");
        }

        $reflection = new ReflectionClass($class);

        //get the constructor
        $contructor = $reflection->getConstructor();
        //if the class doesnt have a constructor we just return a new object
        if (!$contructor){
            return new $class;
        }

        //get the constructor params and try to create the objects for each one
        $constructorParams = $contructor->getParameters();
        $dependencies = array_map(function(ReflectionParameter $parameter){
            //check if it has a default value, and if it the case, then we just use that
            $hasDefaultValue = $parameter->isDefaultValueAvailable();
            if ($hasDefaultValue){
                return $parameter->getDefaultValue();
            }

            $type = $parameter->getType();
            //if type is null or is a builtin object(string, int...) then throw an error, cause we cant make those up
            if (!$type || $type->isBuiltin()){
                throw new \Exception("Cannon resolve class dependency: {$parameter->getName()}");
            }

            //try to resolve the parameter
            return $this->resolve($type->getName());
        },$constructorParams);

        //return the object
        return $reflection->newInstanceArgs($dependencies);
    }
}