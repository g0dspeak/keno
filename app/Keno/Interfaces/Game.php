<?php

namespace App\Keno\Interfaces;


use App\Keno\Results;

interface Game
{
    public function __construct($data);

    /**
     * @return $this
     */
    public function play();

    /**
     * @return Results
     */
    public function getResults();
}
