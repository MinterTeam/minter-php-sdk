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
     * @param float $num
     * @param string $to
     * @return int
     */
    public static function convertValue(float $num, string $to)
    {
        if ($to === 'pip') {
            return intval(self::DEFAULT * $num);
        } else if ($to === 'bip') {
            return $num / self::DEFAULT;
        }
    }

    /**
     * Add nulls to coin name
     *
     * @param string $symbol
     * @return string
     */
    public static function convertCoinName(string $symbol)
    {
        $nulls = $symbol;

        for($i = 1; $i <= 10 - strlen($symbol); $i ++) {
            $nulls .= chr(0);
        }

        return $nulls;
    }
}