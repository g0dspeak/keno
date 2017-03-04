<?php
/**
 * Created by alex.
 * User: alex
 * Date: 25.02.17
 * Time: 12:44
 */

namespace App\Keno;

use App\Keno\Interfaces\Game as GameInterface;


class Game implements GameInterface
{
    const GAMES_AMOUNT = 'games_amount';
    const MAX_WIN = 'max_win';

    public $numbers;
    public $userData;
    public $results;

    public function __construct($data)
    {
        $this->numbers = new Numbers();
        $this->userData = new UserData($data);
        $this->results = new Results($this->userData);
    }

    public function play()
    {
        if ($this->shouldPlayUntilAmountOfGames()) {
            $this->playUntilAmountOfGames();
        } elseif ($this->shouldPlayUntilMaxWin()) {
            $this->playUntilMaxWin();
        }

        $this->results->countTotalProfit();
    }

    protected function playUntilAmountOfGames()
    {
        $gamesAmount = $this->userData->gamesAmount;
        $userCombination = $this->userData->combination;

        for ($i = 0; $i < $gamesAmount; $i++) {
            $randomTwentyNumbers = $this->numbers->newLotteryNumbers();

            $intersect = array_intersect($userCombination, $randomTwentyNumbers);

            $this->results->push($intersect);
        }
    }

    protected function playUntilMaxWin()
    {
        $userCombination = $this->userData->combination;
        $maxWin = $this->userData->maxWin;
        $maxWin = explode('/', $maxWin);
        $needIntersectCount = (int) $maxWin[0];

        for (; $this->results->countOfCurrentIntersect != $needIntersectCount;) {
            $randomTwentyNumbers = $this->numbers->newLotteryNumbers();

            $intersect = array_intersect($userCombination, $randomTwentyNumbers);

            $this->results->push($intersect);
        }
    }

    protected function shouldPlayUntilAmountOfGames()
    {
        return $this->userData->playUntil == self::GAMES_AMOUNT;
    }

    protected function shouldPlayUntilMaxWin()
    {
        return $this->userData->playUntil == self::MAX_WIN;
    }
}
