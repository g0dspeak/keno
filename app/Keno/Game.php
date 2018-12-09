<?php
/**
 * Created by alex.
 * User: alex
 * Date: 25.02.17
 * Time: 12:44
 */

namespace App\Keno;

use App\Keno\Interfaces\Game as GameInterface;
use Carbon\Carbon;


class Game implements GameInterface
{
    const GAME_TIME_LIMIT = 89;

    const GAMES_AMOUNT = 'games_amount';
    const MAX_WIN = 'max_win';
    const MAX_WIN_MONEY = 'max_win_money';

    /**
     * @var Carbon
     */
    protected $timeStarted;

    public $numbers;
    public $userData;
    public $results;

    public function __construct($data)
    {
        $this->timeStarted = Carbon::now();

        $this->numbers = new Numbers();
        $this->userData = new UserData($this->numbers, $data);
        $this->results = new Results($this->userData);
    }

    public function getResults()
    {
        return $this->results;
    }

    public function play()
    {
        if ($this->shouldPlayUntilAmountOfGames()) {
            $this->playUntilAmountOfGames();
        } elseif ($this->shouldPlayUntilMaxWinCombination()) {
            $this->playUntilMaxWin();
        } elseif ($this->shouldPlayUntilMaxWinMoney()) {
            $this->playUntilMaxWinMoney();
        }

        $this->results->countTotalProfit();

        return $this;
    }

    protected function playUntilAmountOfGames()
    {
        $gamesAmount = $this->userData->gamesAmount;

        for ($i = 0; $i < $gamesAmount; $i++) {
            $randomTwentyNumbers = $this->numbers->newLotteryNumbers();

            $userCombination = $this->userData->getCombination();
            $intersect = array_intersect($userCombination, $randomTwentyNumbers);

            $this->results->push($intersect);

            if ($this->timeStarted->diffInSeconds(Carbon::now()) > self::GAME_TIME_LIMIT) {
                $this->results->timeoutError = true;
                break;
            }
        }
    }

    protected function playUntilMaxWin()
    {
        $maxWin = $this->userData->maxWin;
        $maxWin = explode('/', $maxWin);
        $needIntersectCount = (int) $maxWin[0];

        for (; $this->results->countOfCurrentIntersect != $needIntersectCount;) {
            $randomTwentyNumbers = $this->numbers->newLotteryNumbers();

            $userCombination = $this->userData->getCombination();
            $intersect = array_intersect($userCombination, $randomTwentyNumbers);

            $this->results->push($intersect);

            if ($this->timeStarted->diffInSeconds(Carbon::now()) > self::GAME_TIME_LIMIT) {
                $this->results->timeoutError = true;
                break;
            }
        }
    }

    protected function playUntilMaxWinMoney()
    {
        $maxWin = $this->userData->maxWinMoney;

        for (; $this->results->countTotalProfit() < $maxWin;) {
            $randomTwentyNumbers = $this->numbers->newLotteryNumbers();

            $userCombination = $this->userData->getCombination();
            $intersect = array_intersect($userCombination, $randomTwentyNumbers);

            $this->results->push($intersect);

            if ($this->timeStarted->diffInSeconds(Carbon::now()) > self::GAME_TIME_LIMIT) {
                $this->results->timeoutError = true;
                break;
            }
        }
    }

    protected function shouldPlayUntilAmountOfGames()
    {
        return $this->userData->playUntil == self::GAMES_AMOUNT;
    }

    protected function shouldPlayUntilMaxWinCombination()
    {
        return $this->userData->playUntil == self::MAX_WIN;
    }

    protected function shouldPlayUntilMaxWinMoney()
    {
        return $this->userData->playUntil == self::MAX_WIN_MONEY;
    }
}
