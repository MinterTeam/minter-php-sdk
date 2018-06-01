<?php

namespace Minter\SDK;

class MinterConverter
{
    /**
     * PIP in BIP
     */
    const DEFAULT = 100000000;

    /**
     * Convert value
     *
     * @param $num
     * @param string $to
     * @return string
     */
    public static function convertValue($num, string $to)
    {
        if ($to === 'pip') {
            return bcmul(self::DEFAULT, $num);
        } else if ($to === 'bip') {
            return bcdiv($num, self::DEFAULT);
        }
    }

    /**
     * Add nulls to coin name
     *
     * @param string $symbol
     * @return string
     */
    public static function convertCoinName(string $symbol): string
    {
        return $symbol  . str_repeat(chr(0), 10 - strlen($symbol));
    }
}