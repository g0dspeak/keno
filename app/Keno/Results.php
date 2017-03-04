<?php
/**
 * Created by alex.
 * User: alex
 * Date: 26.02.17
 * Time: 7:35
 */

namespace App\Keno;


class Results
{
    public $userData;
    public $winTableSection;

    public $intersects;
    public $winIntersects;
    public $countOfCurrentIntersect = -1;

    public $totalSpent;
    public $totalWin = 0;
    public $totalProfit = 0;

    public function __construct(UserData $userData)
    {
        $this->userData = $userData;
        $this->winTableSection = WinsTable::WINS_TABLE[$this->userData->countOfUserNumbers];
    }

    public function countTotalProfit()
    {
        $this->totalProfit = $this->totalWin - $this->totalSpent;
    }

    public function push($intersect)
    {
        $this->pushToIntersects($intersect);
        $this->pushToWinIntersects($intersect);
    }

    protected function pushToIntersects($intersect)
    {
        $this->intersects[] = $intersect;

        $this->totalSpent += $this->userData->moneyRate;
    }

    protected function pushToWinIntersects($intersect)
    {
        $this->countOfCurrentIntersect = count($intersect);

        if (array_key_exists($this->countOfCurrentIntersect, $this->winTableSection)) {
            $this->winIntersects[$this->countOfCurrentIntersect][] = $intersect;

            $this->totalWin += $this->userData->moneyRate * $this->winTableSection[$this->countOfCurrentIntersect];
        }
    }
}
