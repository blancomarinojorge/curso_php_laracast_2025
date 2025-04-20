<?php

namespace Core;

class Authenticator{
    public static function attemptLogin(string $email, string $password){
        /** @var Database2 $db */
        $db = App::container()->resolve(Database2::class);
        $user = $db->query("select * from users where email = :email",[
            "email" => $email
        ])->fetch();

        if ($user){
            if (password_verify($password, $user["password"])){
                static::login($user);

                return true;
            }
        }

        return false;
    }

    public static function login($user){
        Session::put("user",[
            "email" => $user["email"]
        ]);

        //generates a new session id and deletes the old session file, another layer of security
        session_regenerate_id(true);
    }

    public static function logout(){
        Session::destroy();
    }
}