<?php
/**
 * Created by alex.
 * User: alex
 * Date: 25.02.17
 * Time: 12:50
 */

namespace App\Keno;

use App\Keno\Interfaces\UserData as UserDataInterface;
use App\Keno\Interfaces\Numbers as NumbersInterface;


class UserData implements UserDataInterface
{
    /**
     * @var NumbersInterface
     */
    protected $numbers;

    // user data
    public $combination;
    public $moneyRate;
    public $gamesAmount;
    public $maxWin;
    public $playUntil;
    public $customCombination;
    public $amountOfRandomNumbers;

    public $countOfUserNumbers;

    public function __construct(NumbersInterface $numbers, $data)
    {
        $this->numbers = $numbers;

        $this->combination = $data['combination'] ?? [];
        $this->moneyRate = $data['money_rate'];
        $this->gamesAmount = $data['games_amount'];
        $this->maxWin = $data['max_win'];
        $this->playUntil = $data['play_until'];
        $this->customCombination = (bool) $data['custom_combination'];
        $this->amountOfRandomNumbers = $data['amount_of_random_numbers'];

        $this->countOfUserNumbers = count($this->combination) ?: $data['amount_of_random_numbers'];
    }

    public function getCombination()
    {
        if ($this->customCombination) {
            return $this->combination;
        }

        return $this->numbers->newLotteryNumbers($this->amountOfRandomNumbers);
    }
}