<?php

namespace Core;

class Partes
{
    private Asiento $asiento;
    private Volante $volante;

    /**
     * @param Asiento $asiento
     * @param Volante $volante
     */
    public function __construct(Asiento $asiento, Volante $volante)
    {
        $this->asiento = $asiento;
        $this->volante = $volante;
    }

    public function getAsiento(): Asiento
    {
        return $this->asiento;
    }

    public function setAsiento(Asiento $asiento): void
    {
        $this->asiento = $asiento;
    }

    public function getVolante(): Volante
    {
        return $this->volante;
    }

    public function setVolante(Volante $volante): void
    {
        $this->volante = $volante;
    }
}