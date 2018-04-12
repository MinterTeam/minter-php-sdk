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
    public static function convertValue(float $num, string $to): int
    {
        if ($to === 'pip') {
            return self::DEFAULT * $num;
        } else if ($to === 'bip') {
            return self::DEFAULT / $num;
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