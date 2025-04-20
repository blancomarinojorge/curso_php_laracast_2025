<?php

namespace Http\Forms;

class LoginForm{

    protected array $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    public function validate(string $email, string $password): bool{
        if ($email==="" || $password===""){
            $this->addError("loginError","Provide a valid email and password");
        }
        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(string $key, string $content){
        $this->errors[$key] = $content;
    }
}