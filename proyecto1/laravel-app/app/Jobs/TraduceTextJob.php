<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TraduceTextJob implements ShouldQueue
{
    use Queueable;

    public String $texto;

    public function __construct(String $texto)
    {
        $this->texto = $texto;
    }

    public function handle(): void
    {
        logger('Traducindo...'.$this->texto);
    }
}
