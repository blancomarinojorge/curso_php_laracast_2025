<?php

namespace Core;

class Container{
    private array $bindings;

    public function __construct(){
        $this->bindings = [];
    }

    public function bind(string $key, callable $func){
        $this->bindings[$key] = $func;
    }

    public function resolve($key){
        if (!array_key_exists($key, $this->bindings)){
            throw new \Exception("Couldnt resolve the the binding with key {$key}");
        }

        return $this->bindings[$key]();
    }
}