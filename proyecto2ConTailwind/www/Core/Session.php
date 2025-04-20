<?php

namespace Core;

class Session{
    public static function has($key): bool{
        return (bool) static::get($key);
    }

    public static function put(string $key, $value){
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null){
        return $_SESSION["__flashed"][$key] ?? $_SESSION[$key] ?? $default;
    }

    public static function flush(){
        $_SESSION = [];
    }

    public static function destroy(){
        static::flush();
        session_destroy();

        $sessionCookieData = session_get_cookie_params();
        setcookie("PHPSESSID", "", time() - 3600, $sessionCookieData["path"], $sessionCookieData["domain"], $sessionCookieData["secure"], $sessionCookieData["httponly"]);
    }

    //FLASHING
    public static function getFlashed(string $key, $default = null){
        return $_SESSION["__flashed"][$key] ?? $default;
    }

    public static function flash(string $key, $value){
        $_SESSION["__flashed"][$key] = $value;
    }

    public static function unflash(){
        unset($_SESSION["__flashed"]);
    }


}