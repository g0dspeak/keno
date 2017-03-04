<?php

namespace App\Keno\Interfaces;


interface Game
{
    public function __construct($data);
    public function play();
}
