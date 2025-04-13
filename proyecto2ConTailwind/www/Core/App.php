<?php

namespace Core;

class App{
    private static Container $container;

    public static function setContainer(Container $container){
        static::$container = $container;
    }

    public static function container(): Container{
        return static::$container; //static é mellor que facer App::container porque se se accede dende unha subclase a este metodo e esta sobreescribe $container vai pillar o da subclase, non o de App
    }
}