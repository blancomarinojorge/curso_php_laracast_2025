<?php

namespace Http\Forms;

class ValidationFormException extends \Exception{
    private array $errors;
    private array $old;

    public function __construct(array $errors, array $old){
        parent::__construct();
        $this->errors = $errors;
        $this->old = $old;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getOld(): array
    {
        return $this->old;
    }
}