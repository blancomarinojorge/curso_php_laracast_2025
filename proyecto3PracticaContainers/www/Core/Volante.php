<?php

namespace Core;

class Volante
{
    private int $ancho;
    public function __construct(int $ancho = 15)
    {
        $this->ancho = $ancho;
    }

    public function getAncho(): int
    {
        return $this->ancho;
    }

    public function setAncho(int $ancho): void
    {
        $this->ancho = $ancho;
    }


}