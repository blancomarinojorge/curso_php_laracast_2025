<?php

namespace Http\Forms;

use Core\Validator;

class LoginForm{

    private const MIN_PASSWORD_LENGTH= 5;

    protected array $errors;
    protected array $old;

    public function __construct(array $attributes)
    {
        $email = $attributes["email"] ?? '';
        $password = $attributes["password"] ?? '';

        $this->old["email"] = $email;

        if (!Validator::email($email)){
            $this->addError("emailError","Provide a valid email");
        }
        if (!Validator::checkString($password, 5)){
            $this->addError("passwordError", "Provide a valid password(min ".self::MIN_PASSWORD_LENGTH." char)");
        }
    }

    /**
     * @param array $attributes
     * @return LoginForm
     * @throws ValidationFormException
     */
    public static function validate(array $attributes): LoginForm{
        $instance = new static($attributes);

        if (!$instance->validationPassed()){
            $instance->throw();
        }

        return $instance;
    }

    /**
     * @return void
     * @throws ValidationFormException
     */
    public function throw(){
        throw new ValidationFormException($this->errors, $this->old);
    }

    public function validationPassed(): bool{
        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(string $key, string $content): LoginForm{
        $this->errors[$key] = $content;
        return $this;
    }
}