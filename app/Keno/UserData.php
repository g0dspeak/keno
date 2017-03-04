<?php
/**
 * Created by alex.
 * User: alex
 * Date: 25.02.17
 * Time: 12:50
 */

namespace App\Keno;

use App\Keno\Interfaces\UserData as UserDataInterface;


class UserData implements UserDataInterface
{
    public $combination;
    public $moneyRate;
    public $gamesAmount;
    public $maxWin;
    public $playUntil;

    public $countOfUserNumbers;

    public function __construct($data)
    {
        $this->combination = $data['combination'];
        $this->moneyRate = $data['money_rate'];
        $this->gamesAmount = $data['games_amount'];
        $this->maxWin = $data['max_win'];
        $this->playUntil = $data['play_until'];

        $this->countOfUserNumbers = count($this->combination);
    }
}