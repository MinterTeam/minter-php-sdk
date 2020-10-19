<?php

namespace Minter\SDK;

use Minter\Library\Helper;
use InvalidArgumentException;

/**
 * Class MinterConverter
 * @package Minter\SDK
 */
class MinterConverter
{
    /**
     * PIP in BIP
     */
    const DEFAULT = '1000000000000000000';

    /**
     * @param string $num
     * @return string
     */
    public static function convertToPip(string $num): string
    {
        return bcmul(self::DEFAULT, $num, 0);
    }

    /**
     * @param string $num
     * @return string
     */
    public static function convertToBase(string $num): string
    {
        $num = bcdiv($num, self::DEFAULT, 25);
        return Helper::niceNumber($num);
    }

    /**
     * Add nulls to coin name
     *
     * @param string $symbol
     * @return string
     */
    public static function convertCoinName(string $symbol): string
    {
        $countOfNulls = 10 - strlen($symbol);
        if($countOfNulls < 0) {
            throw new InvalidArgumentException('Coin name could have no more than 10 symbols.');
        }

        return $symbol  . str_repeat(chr(0), $countOfNulls);
    }
}