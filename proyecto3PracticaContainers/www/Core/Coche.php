<?php

namespace Core;

class Coche{
    private Conductor $conductor;
    private Partes $partes;

    /**
     * @param Conductor $conductor
     * @param Partes $partes
     */
    public function __construct(Conductor $conductor, Partes $partes)
    {
        $this->conductor = $conductor;
        $this->partes = $partes;
    }

    public function getConductor(): Conductor
    {
        return $this->conductor;
    }

    public function setConductor(Conductor $conductor): void
    {
        $this->conductor = $conductor;
    }

    public function getPartes(): Partes
    {
        return $this->partes;
    }

    public function setPartes(Partes $partes): void
    {
        $this->partes = $partes;
    }
}