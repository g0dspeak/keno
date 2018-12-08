<?php

namespace App\Keno\Interfaces;


interface Numbers
{
    public function __construct();

    /**
     * Generate random numbers from numbers table
     *
     * @param int $amount
     * @return mixed
     */
    public function newLotteryNumbers($amount = 20);
}