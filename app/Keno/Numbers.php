<?php
/**
 * Created by alex.
 * User: alex
 * Date: 18.02.17
 * Time: 23:19
 */

namespace App\Keno;

use App\Keno\Interfaces\Numbers as NumberInterface;


class Numbers implements NumberInterface
{
    public $numbers;

    public function __construct()
    {
        $this->numbers = range(1, 70);
    }

    public function newLotteryNumbers()
    {
        $numbers = $this->numbers;

        $randomTwentyNumbers = [];
        for ($i = 0; $i < 20; $i++) {
            $randomKey = array_rand($numbers);
            $randomValue = $numbers[$randomKey];

            unset($numbers[$randomKey]);

            $randomTwentyNumbers[] = $randomValue;
        }

        return $randomTwentyNumbers;
    }
}
